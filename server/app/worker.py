import time
from app.config import MAX_CONCURRENT_JOBS
from app.db.database import SessionLocal
from app.db.crud import get_next_queued_jobs, update_queue_status, create_speech_entry, get_speech_by_hash
from app.services.piper import generate_speech
from app.core.utils import get_audio_path, generate_text_hash, get_audio_file_info
from app.core.logger import get_logger

# Get logger
logger = get_logger()

def process_job(db, queue_entry):
    """Process a single job from the queue"""
    try:
        # Update status to processing
        update_queue_status(db, queue_entry.id, 'processing')
        logger.info(f"Processing job {queue_entry.id} for request {queue_entry.request_id}")
        
        # Get request details
        request = queue_entry.request
        language_code = request.language.code
        voice_name = request.voice.name
        text = request.text
        
        # Generate hash for this text/language/voice combination
        hash_value = generate_text_hash(text, language_code, voice_name)
        
        # Check if we already have this speech in the database
        existing_speech = get_speech_by_hash(db, hash_value)
        if existing_speech:
            logger.info(f"Found existing speech with hash {hash_value}")
            # Create a new speech entry linking to the same file
            speech = create_speech_entry(
                db, 
                request.id, 
                hash_value, 
                existing_speech.file_path,
                existing_speech.file_size,
                existing_speech.duration_ms
            )
            update_queue_status(db, queue_entry.id, 'completed')
            return True
        
        # Generate the audio file path
        output_path = get_audio_path(hash_value)
        
        # Generate speech
        success, error_message = generate_speech(text, language_code, voice_name, output_path)
        
        if success:
            # Get file info
            file_info = get_audio_file_info(output_path)
            
            # Create speech entry
            speech = create_speech_entry(
                db,
                request.id,
                hash_value,
                output_path,
                file_info["file_size"],
                file_info["duration_ms"]
            )
            
            # Update queue status
            update_queue_status(db, queue_entry.id, 'completed')
            logger.info(f"Successfully processed job {queue_entry.id}")
            return True
        else:
            # Update queue status with error
            update_queue_status(db, queue_entry.id, 'failed', error_message)
            logger.error(f"Failed to process job {queue_entry.id}: {error_message}")
            return False
    
    except Exception as e:
        logger.exception(f"Error processing job {queue_entry.id}")
        update_queue_status(db, queue_entry.id, 'failed', str(e))
        return False

def run_worker():
    """Main worker loop"""
    logger.info("Starting worker process")
    
    while True:
        db = SessionLocal()
        try:
            # Get queued jobs
            queued_jobs = get_next_queued_jobs(db, MAX_CONCURRENT_JOBS)
            
            # Process each job
            for job in queued_jobs:
                process_job(db, job)
            
            # If no jobs were found, sleep for a bit
            if not queued_jobs:
                time.sleep(1)
        
        except Exception as e:
            logger.exception("Error in worker process")
        
        finally:
            db.close()

if __name__ == "__main__":
    run_worker()