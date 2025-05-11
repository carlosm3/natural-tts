import requests

from app.config import GEOLOCATION_API_URL
from app.core.logger import get_logger

logger = get_logger()


def get_geo_info(ip):
    """
    Get geolocation information for an IP address.

    Returns a dictionary with country, region, and city.
    """
    try:
        response = requests.get(f"{GEOLOCATION_API_URL}?ip={ip}", timeout=3)
        if response.status_code == 200:
            data = response.json()
            return {
                "country": data.get("country", ""),
                "region": data.get("regionName", ""),
                "city": data.get("city", ""),
            }
    except Exception as e:
        logger.warning(f"Failed to get geo info for IP {ip}: {str(e)}")

    # Default empty values if lookup failed
    return {"country": "", "region": "", "city": ""}
