from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

from app.db.database import create_tables
from app.routes import router
from app.config import ALLOWED_ORIGINS

# Create the FastAPI app
app = FastAPI(
    title="Natural TTS API",
    description="API for text-to-speech conversion using Piper",
    version="1.0.0",
)

# Add CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=ALLOWED_ORIGINS,
    allow_credentials=True,
    allow_methods=["GET", "POST"],
    allow_headers=["*"],
)

# Include the router
app.include_router(router)

# Startup event to create tables
@app.on_event("startup")
def on_startup():
    create_tables()