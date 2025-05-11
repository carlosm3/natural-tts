from datetime import datetime
from sqlalchemy.orm import Session
from app.db.models import Language, Voice, Request, Queue, Speech

def get_or_create_language(db: Session, code: str, name: str):
    """Get or create a language entry"""
    language = db.query(Language).filter(Language.code == code).first()
    if not language:
        language = Language(code=code, name=name)
        db.add(language)
        db.commit()
        db.refresh(language)
    return language

def get_or_create_voice(db: Session, name: str, language_id: int):
    """Get or create a voice entry"""
    voice = db.query(Voice).filter(Voice.name == name, Voice.language_id == language_id).first()
    if not voice:
        voice = Voice(name=name, language_id=language_id)
        db.add(voice)
        db.commit()
        db.refresh(voice)
    return voice

def create_request(db: Session, text: str, language_id: int, voice_id: int, ip_address: str, user_agent: str, country=None, region=None, city=None):
    """Create a new request"""
    request = Request(
        text=text,
        language_id=language_id,
        voice_id=voice_id,
        ip_address=ip_address,
        user_agent=user_agent,
        country=country,
        region=region,
        city=city
    )
    db.add(request)
    db.commit()
    db.refresh(request)
    return request

def create_queue_entry(db: Session, request_id: str):
    """Create a queue entry for a request"""
    queue_entry = Queue(request_id=request_id)
    db.add(queue_entry)
    db.commit()
    db.refresh(queue_entry)
    return queue_entry

def update_queue_status(db: Session, queue_id: int, status: str, error_message=None):
    """Update queue entry status"""
    queue_entry = db.query(Queue).filter(Queue.id == queue_id).first()
    if queue_entry:
        queue_entry.status = status
        if status == 'processing':
            queue_entry.started_at = datetime.now()
        elif status in ['completed', 'failed']:
            queue_entry.completed_at = datetime.now()
        if error_message:
            queue_entry.error_message = error_message
        db.commit()
        db.refresh(queue_entry)
    return queue_entry

def create_speech_entry(db: Session, request_id: str, hash_value: str, file_path: str, file_size=None, duration_ms=None):
    """Create a speech entry"""
    speech = Speech(
        request_id=request_id,
        hash=hash_value,
        file_path=file_path,
        file_size=file_size,
        duration_ms=duration_ms
    )
    db.add(speech)
    db.commit()
    db.refresh(speech)
    return speech

def get_queue_entry_by_request_id(db: Session, request_id: str):
    """Get queue entry by request ID"""
    return db.query(Queue).filter(Queue.request_id == request_id).first()

def get_speech_by_hash(db: Session, hash_value: str):
    """Get speech entry by hash"""
    return db.query(Speech).filter(Speech.hash == hash_value).first()

def get_next_queued_jobs(db: Session, limit: int):
    """Get next queued jobs to process"""
    processing_count = db.query(Queue).filter(Queue.status == 'processing').count()
    available_slots = max(0, limit - processing_count)
    
    if available_slots > 0:
        queued_jobs = db.query(Queue).filter(Queue.status == 'queued').order_by(Queue.queued_at).limit(available_slots).all()
        return queued_jobs
    
    return []