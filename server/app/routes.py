from fastapi import APIRouter, Depends, HTTPException, Request, status
from fastapi.responses import FileResponse
from sqlalchemy.orm import Session
from typing import List
import os

from app.config import MAX_CHAR_LIMIT
from app.db.database import get_db
from app.db.models import Request as RequestModel
from app.db.crud import (
    get_or_create_language, get_or_create_voice,
    create_request, create_queue_entry, get_queue_entry_by_request_id
)
from app.core.utils import get_client_ip, get_geolocation_data
from app.services.piper import get_available_languages, get_available_voices
from app.schemas import (
    TTSRequest, LanguageResponse, VoiceResponse,
    TTSJobResponse, TTSStatusResponse
)

# Create router instance
router = APIRouter()

# Health check endpoint
@router.get("/health")
def health_check():
    return {"status": "ok"}

# Get available languages
@router.get("/languages", response_model=List[LanguageResponse])
def get_languages():
    return get_available_languages()

# Get available voices
@router.get("/voices", response_model=List[VoiceResponse])
def get_voices():
    return get_available_voices()

# Create a TTS job
@router.post("/create", response_model=TTSJobResponse)
async def create_tts_job(
    request: Request,
    tts_request: TTSRequest,
    db: Session = Depends(get_db)
):
    # Validate text length
    if len(tts_request.text) > MAX_CHAR_LIMIT:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail=f"Text exceeds maximum length of {MAX_CHAR_LIMIT} characters"
        )
    
    # Get client IP and user agent
    ip_address = await get_client_ip(request)
    user_agent = request.headers.get("User-Agent", "")
    
    # Get geolocation data
    geo_data = await get_geolocation_data(ip_address)
    
    # Get or create language
    language_name = next((lang["name"] for lang in get_available_languages() 
                          if lang["code"] == tts_request.language), tts_request.language)
    language = get_or_create_language(db, tts_request.language, language_name)
    
    # Get or create voice
    voice = get_or_create_voice(db, tts_request.voice, language.id)
    
    # Create request
    tts_request_obj = create_request(
        db,
        tts_request.text,
        language.id,
        voice.id,
        ip_address,
        user_agent,
        country=geo_data.get("country"),
        region=geo_data.get("regionName"),
        city=geo_data.get("city")
    )
    
    # Create queue entry
    queue_entry = create_queue_entry(db, tts_request_obj.id)
    
    return {"job_id": tts_request_obj.id, "status": queue_entry.status}

# Get TTS job status
@router.get("/status/{job_id}", response_model=TTSStatusResponse)
def get_tts_status(job_id: str, db: Session = Depends(get_db)):
    # Get queue entry
    queue_entry = get_queue_entry_by_request_id(db, job_id)
    
    if not queue_entry:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Job not found: {job_id}"
        )
    
    return {
        "job_id": job_id,
        "status": queue_entry.status,
        "error_message": queue_entry.error_message
    }

# Get audio file
@router.get("/audio/{job_id}")
def get_audio(job_id: str, db: Session = Depends(get_db)):
    # Get request and speech
    request_obj = db.query(RequestModel).filter(RequestModel.id == job_id).first()
    
    if not request_obj or not request_obj.speech:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Audio not found for job: {job_id}"
        )
    
    # Check if file exists
    file_path = request_obj.speech.file_path
    if not os.path.exists(file_path):
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Audio file not found on server"
        )
    
    return FileResponse(
        file_path,
        media_type="audio/mpeg",
        filename=f"{job_id}.mp3"
    )
