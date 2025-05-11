import hashlib
import os
import aiohttp
from fastapi import Request
from app.config import AUDIO_CACHE_DIR, GEOLOCATION_API_URL

async def get_client_ip(request: Request) -> str:
    """Extract client IP from request headers or client host"""
    if "CF-Connecting-IP" in request.headers:
        return request.headers["CF-Connecting-IP"]
    return request.client.host

async def get_geolocation_data(ip_address: str) -> dict:
    """Get geolocation data for an IP address"""
    async with aiohttp.ClientSession() as session:
        async with session.get(f"{GEOLOCATION_API_URL}?ip={ip_address}") as response:
            if response.status == 200:
                data = await response.json()
                return data
            return {}

def generate_text_hash(text: str, language_code: str, voice_name: str) -> str:
    """Generate a hash value for the text, language, and voice"""
    hash_input = f"{text}_{language_code}_{voice_name}"
    return hashlib.sha256(hash_input.encode()).hexdigest()

def get_audio_path(hash_value: str) -> str:
    """Get the file path for an audio file based on its hash"""
    return os.path.join(AUDIO_CACHE_DIR, f"{hash_value}.mp3")

def get_audio_file_info(file_path: str) -> dict:
    """Get information about an audio file (size, etc.)"""
    if os.path.exists(file_path):
        file_size = os.path.getsize(file_path)
        # For simplicity, we're not calculating actual duration
        # In a real implementation, you'd use a library like pydub
        return {
            "file_size": file_size,
            "duration_ms": None
        }
    return {"file_size": None, "duration_ms": None}