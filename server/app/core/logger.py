import os
import pathlib
import sys

from dotenv import load_dotenv
from loguru import logger

load_dotenv()

# Get server root directory (parent of app directory)
SERVER_ROOT = pathlib.Path(__file__).parent.parent.parent.absolute()

# Get log file path from environment variables, default to root of server
LOG_FILE = os.getenv("LOG_FILE", "app.log")

# If LOG_FILE is a relative path, make it relative to SERVER_ROOT instead of current working directory
if not os.path.isabs(LOG_FILE):
    LOG_FILE = os.path.join(SERVER_ROOT, LOG_FILE)

# Ensure directory exists if log file has a directory component
log_dir = os.path.dirname(LOG_FILE)
if log_dir:
    os.makedirs(log_dir, exist_ok=True)

# Configure logger
logger.remove()  # Remove default handlers
logger.add(sys.stderr, level="INFO")  # Add stderr handler
logger.add(
    LOG_FILE,
    rotation="10 MB",
    retention="7 days",
    level="INFO",
    backtrace=True,
    diagnose=True,
)


def get_logger():
    return logger
