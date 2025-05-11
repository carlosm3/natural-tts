#!/bin/bash

# Exit on error
set -e

echo "Setting up Natural TTS API with Piper..."

# Install system dependencies
echo "Installing system dependencies..."
sudo apt update
sudo apt install -y build-essential python3-dev python3-pip mariadb-server libmariadb-dev

# Install Python dependencies
echo "Installing Python dependencies..."
pip install -r requirements.txt

# Set up MariaDB
echo "Setting up MariaDB..."
sudo systemctl start mariadb
sudo systemctl enable mariadb

# Secure MariaDB installation if not already done
if ! mysql -u root -e "SHOW DATABASES" >/dev/null 2>&1; then
    sudo mysql_secure_installation
fi

# Create database and user
echo "Creating database and user..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS naturaltts;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'naturaltts'@'localhost' IDENTIFIED BY 'password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON naturaltts.* TO 'naturaltts'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Install Piper
echo "Cloning and building Piper..."
if [ ! -d "piper" ]; then
    git clone https://github.com/rhasspy/piper
    cd piper
    # Building Piper (this would need to be adapted based on Piper's build instructions)
    make
    sudo cp piper /usr/local/bin/
    cd ..
fi

# Create directories
echo "Creating directories..."
mkdir -p audio_cache models

# Download voice models for top 10 languages
echo "Downloading voice models..."
# This would need to be adapted based on where Piper models are hosted
# Example: 
# wget -P models/en/ https://path/to/piper/models/en/ljspeech.onnx

# Set up systemd service for worker
echo "Setting up worker service..."
cat > /tmp/naturaltts-worker.service << EOF
[Unit]
Description=Natural TTS Worker
After=network.target mariadb.service

[Service]
User=$(whoami)
WorkingDirectory=$(pwd)
ExecStart=$(which python3) -m app.worker
Restart=on-failure
RestartSec=5s

[Install]
WantedBy=multi-user.target
EOF

sudo mv /tmp/naturaltts-worker.service /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable naturaltts-worker
sudo systemctl start naturaltts-worker

echo "Setup complete! You can now start the API server with:"
echo "python -m app.main"