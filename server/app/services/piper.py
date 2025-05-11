import subprocess
import os
from typing import Tuple, List

from app.config import PIPER_PATH, PIPER_MODELS_DIR
from app.core.logger import get_logger

logger = get_logger()

def get_voice_model_path(language_code: str, voice_name: str) -> str:
    """Get the path to a voice model"""
    return os.path.join(PIPER_MODELS_DIR, language_code, f"{voice_name}.onnx")

def get_available_languages() -> List[dict]:
    """Get list of available languages"""
    languages = [
        {"code": "en", "name": "English"},
        {"code": "es", "name": "Spanish"},
        {"code": "fr", "name": "French"},
        {"code": "de", "name": "German"},
        {"code": "zh", "name": "Chinese"},
        {"code": "ja", "name": "Japanese"},
        {"code": "ru", "name": "Russian"},
        {"code": "pt", "name": "Portuguese"},
        {"code": "ar", "name": "Arabic"},
        {"code": "hi", "name": "Hindi"}
    ]
    return languages

def get_available_voices() -> List[dict]:
    """Get list of available voices by language"""
    voices = [
        {"language_code": "en", "name": "ljspeech"},
        {"language_code": "en", "name": "libritts"},
        {"language_code": "es", "name": "css10"},
        {"language_code": "fr", "name": "css10"},
        {"language_code": "de", "name": "css10"},
        {"language_code": "zh", "name": "css10"},
        {"language_code": "ja", "name": "css10"},
        {"language_code": "ru", "name": "css10"},
        {"language_code": "pt", "name": "css10"},
        {"language_code": "ar", "name": "nawar"},
        {"language_code": "hi", "name": "cmu_indic"}
    ]
    return voices

def generate_speech(text: str, language_code: str, voice_name: str, output_path: str) -> Tuple[bool, str]:
    """
    Generate speech using Piper
    
    Args:
        text: The text to convert to speech
        language_code: The language code (e.g., 'en')
        voice_name: The voice name (e.g., 'ljspeech')
        output_path: The path to save the audio file
        
    Returns:
        Tuple of (success, error_message)
    """
    try:
        # Get the voice model path
        model_path = get_voice_model_path(language_code, voice_name)
        if not os.path.exists(model_path):
            return False, f"Voice model not found: {model_path}"
        
        # Create temp text file
        text_file = output_path + ".txt"
        with open(text_file, "w", encoding="utf-8") as f:
            f.write(text)
        
        # Run Piper
        command = [
            PIPER_PATH,
            "--model", model_path,
            "--output_file", output_path,
            "--text_file", text_file
        ]
        
        result = subprocess.run(command, capture_output=True, text=True)
        
        # Clean up temp file
        if os.path.exists(text_file):
            os.remove(text_file)
        
        # Check if output file was created
        if os.path.exists(output_path) and os.path.getsize(output_path) > 0:
            return True, ""
        else:
            return False, f"Failed to generate speech: {result.stderr}"
    
    except Exception as e:
        logger.error(f"Error generating speech: {str(e)}")
        return False, str(e)