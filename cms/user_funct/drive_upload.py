#!/usr/bin/env python3
import os
import sys
from google.oauth2 import service_account
from googleapiclient.discovery import build
from googleapiclient.http import MediaFileUpload

# Use the Drive file scope
SCOPES = ['https://www.googleapis.com/auth/drive.file']
SERVICE_ACCOUNT_FILE = os.path.join(os.path.dirname(__file__), 'photos.json')

def upload_to_drive(file_path):
    # Authenticate using the service account key file
    credentials = service_account.Credentials.from_service_account_file(
        SERVICE_ACCOUNT_FILE,
        scopes=SCOPES
    )
    service = build('drive', 'v3', credentials=credentials)

    # Use a specific Google Drive folder by its ID
    folder_id = "1v-PtfYnFqUDH6JO7KX9Q-_tykek0NynO"
    
    # Upload the file into the specified folder
    file_metadata = {
        'name': os.path.basename(file_path),
        'parents': [folder_id]
    }
    media = MediaFileUpload(file_path, mimetype='image/*')
    uploaded_file = service.files().create(
        body=file_metadata,
        media_body=media,
        fields='id'
    ).execute()

    file_id = uploaded_file.get('id')

    # Share the uploaded file with your personal account
    permission = {
        'type': 'anyone',
        'role': 'reader'
    }
    service.permissions().create(
        fileId=file_id,
        body=permission,
        fields='id'
    ).execute()

    # Return a URL to access the file
    drive_url = f"https://drive.google.com/uc?id={file_id}"
    return drive_url

if __name__ == '__main__':
    if len(sys.argv) == 2:
        file_path = sys.argv[1]
        drive_url = upload_to_drive(file_path)
        print(drive_url)
    else:
        print("Usage: {} <file_path>".format(sys.argv[0]))
