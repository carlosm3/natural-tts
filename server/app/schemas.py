from pydantic import BaseModel, Field
from typing import Optional, List

from app.config import MAX_CHAR_LIMIT

# Pydantic models for request validation
class TTSRequest(BaseModel):
    text: str = Field(..., min_length=1, max_length=MAX_CHAR_LIMIT)
    language: str
    voice: str

class LanguageResponse(BaseModel):
    code: str
    name: str

class VoiceResponse(BaseModel):
    language_code: str
    name: str

class TTSJobResponse(BaseModel):
    job_id: str
    status: str

class TTSStatusResponse(BaseModel):
    job_id: str
    status: str
    error_message: Optional[str] = None
