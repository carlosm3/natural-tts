import os
from pathlib import Path
from typing import List

# Base directories
BASE_DIR = Path(__file__).resolve().parent.parent
AUDIO_CACHE_DIR = os.path.join(BASE_DIR, "audio_cache")
MODELS_DIR = os.path.join(BASE_DIR, "models")

# Create directories if they don't exist
os.makedirs(AUDIO_CACHE_DIR, exist_ok=True)
os.makedirs(MODELS_DIR, exist_ok=True)

# API settings
API_HOST = "0.0.0.0"
API_PORT = 8000
MAX_CHAR_LIMIT = 3000

# Database settings
DATABASE_URL = "mysql+pymysql://naturaltts:password@localhost/naturaltts"

# Piper settings
PIPER_PATH = "/usr/local/bin/piper"
PIPER_MODELS_DIR = MODELS_DIR

# Geolocation API
GEOLOCATION_API_URL = "https://n6eojtbk8b.execute-api.us-east-1.amazonaws.com/prod"

# Worker settings
MAX_CONCURRENT_JOBS = 2

# Environment settings
ENVIRONMENT = os.getenv("ENVIRONMENT", "development")  # 'development' or 'production'

# CORS settings
def get_allowed_origins() -> List[str]:
    if ENVIRONMENT == "development":
        # Allow all origins in development mode
        return ["*"]
    else:
        # Only allow specific origins in production
        return ["https://naturaltts.io", "https://www.naturaltts.io"]

ALLOWED_ORIGINS = get_allowed_origins()