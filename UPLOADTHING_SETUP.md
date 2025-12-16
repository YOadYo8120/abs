# UploadThing Setup for File Uploads

This application uses UploadThing to handle file uploads (PDFs for resources). UploadThing provides 2GB of free storage.

## Setup Instructions

### 1. Create an UploadThing Account

1. Go to https://uploadthing.com
2. Sign up or log in
3. Create a new application

### 2. Get Your API Keys

1. In your UploadThing dashboard, go to **API Keys**
2. Copy your **Secret Key**
3. Copy your **App ID**

### 3. Add Environment Variables

Add these to your Vercel environment variables:

```env
UPLOADTHING_SECRET=your_secret_key_here
UPLOADTHING_APP_ID=your_app_id_here
```

#### In Vercel:
1. Go to your project settings: https://vercel.com/yoadyo8120/abs/settings/environment-variables
2. Add both variables
3. Redeploy your application

#### For Local Development:
Add the same variables to your `.env` file:
```env
UPLOADTHING_SECRET=your_secret_key_here
UPLOADTHING_APP_ID=your_app_id_here
```

## How It Works

### File Upload Flow
1. Teacher uploads a PDF through the Resources page
2. Laravel sends the file to UploadThing via API
3. UploadThing stores the file and returns a unique key
4. The key is stored in the database (`resources.file_path`)
5. Files are accessible at: `https://utfs.io/f/{key}`

### File Download
When a user clicks to download a resource, they're redirected to the UploadThing CDN URL for fast, direct download.

### File Deletion
When a teacher deletes a resource, the file is removed from both UploadThing and the database.

## Implementation Files

- **Service**: `app/Services/UploadThingService.php` - Handles all UploadThing API calls
- **Controller**: `app/Http/Controllers/Teacher/ResourceController.php` - Uses the service for uploads
- **Config**: `config/services.php` - Contains UploadThing configuration

## Storage Limits

- **Free Tier**: 2GB total storage
- **Max File Size**: 50MB per file (configurable in the upload form)
- **Allowed Types**: PDF only

## Testing

After setting up your API keys:

1. Log in as a teacher
2. Go to Resources → Upload Resource
3. Try uploading a PDF file
4. Verify it appears in your UploadThing dashboard
5. Test downloading the file
6. Test deleting the file

## Troubleshooting

### Upload fails with "Failed to get upload URL"
- Check that `UPLOADTHING_SECRET` is set correctly
- Verify the API key is valid in your UploadThing dashboard

### Files don't appear in UploadThing dashboard
- Check that `UPLOADTHING_APP_ID` matches your application
- Verify the upload was successful (check Laravel logs)

### Download links don't work
- Files are stored with their key in the database
- URL format: `https://utfs.io/f/{key}`
- Check that the key exists in your UploadThing dashboard
