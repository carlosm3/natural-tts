from sqlalchemy import Column, Integer, String, Text, Enum, ForeignKey, DateTime, Index, UniqueConstraint
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import relationship
from sqlalchemy.sql import func
import uuid
from datetime import datetime

Base = declarative_base()

class Language(Base):
    __tablename__ = "languages"
    
    id = Column(Integer, primary_key=True, autoincrement=True)
    code = Column(String(10), unique=True, nullable=False)
    name = Column(String(50), nullable=False)
    
    voices = relationship("Voice", back_populates="language")

class Voice(Base):
    __tablename__ = "voices"
    
    id = Column(Integer, primary_key=True, autoincrement=True)
    name = Column(String(50), nullable=False)
    language_id = Column(Integer, ForeignKey("languages.id"), nullable=False)
    
    language = relationship("Language", back_populates="voices")
    
    __table_args__ = (
        UniqueConstraint('name', 'language_id', name='uix_voice_language'),
    )

class Request(Base):
    __tablename__ = "requests"
    
    id = Column(String(36), primary_key=True, default=lambda: str(uuid.uuid4()))
    text = Column(Text, nullable=False)
    language_id = Column(Integer, ForeignKey("languages.id"), nullable=False)
    voice_id = Column(Integer, ForeignKey("voices.id"), nullable=False)
    created_at = Column(DateTime, server_default=func.now())
    
    # User analytics fields
    ip_address = Column(String(45), nullable=False)
    country = Column(String(100))
    region = Column(String(100))
    city = Column(String(100))
    user_agent = Column(Text)
    
    # Relationships
    language = relationship("Language")
    voice = relationship("Voice")
    queue = relationship("Queue", back_populates="request", uselist=False)
    speech = relationship("Speech", back_populates="request", uselist=False)
    
    __table_args__ = (
        Index('idx_ip_address', 'ip_address'),
    )

class Queue(Base):
    __tablename__ = "queue"
    
    id = Column(Integer, primary_key=True, autoincrement=True)
    request_id = Column(String(36), ForeignKey("requests.id"), nullable=False)
    status = Column(Enum('queued', 'processing', 'completed', 'failed'), nullable=False, default='queued')
    error_message = Column(Text)
    queued_at = Column(DateTime, server_default=func.now())
    started_at = Column(DateTime, nullable=True)
    completed_at = Column(DateTime, nullable=True)
    
    # Relationships
    request = relationship("Request", back_populates="queue")
    
    __table_args__ = (
        Index('idx_status', 'status'),
    )

class Speech(Base):
    __tablename__ = "speech"
    
    id = Column(Integer, primary_key=True, autoincrement=True)
    request_id = Column(String(36), ForeignKey("requests.id"), nullable=False)
    hash = Column(String(64), nullable=False, unique=True)
    file_path = Column(String(255), nullable=False)
    file_size = Column(Integer)
    duration_ms = Column(Integer)
    created_at = Column(DateTime, server_default=func.now())
    
    # Relationships
    request = relationship("Request", back_populates="speech")
    
    __table_args__ = (
        Index('idx_hash', 'hash'),
        Index('idx_request_id', 'request_id'),
    )
