from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker

from app.config import DATABASE_URL
from app.db.models import Base

# Create SQLAlchemy engine
engine = create_engine(DATABASE_URL)

# Session factory
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)

# Create tables in database
def create_tables():
    Base.metadata.create_all(bind=engine)

# Get a database session
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()