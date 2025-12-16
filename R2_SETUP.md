# Cloudflare R2 Setup Guide

## Getting Your R2 Credentials

### 1. Get Your Account ID
1. Go to [Cloudflare Dashboard](https://dash.cloudflare.com/)
2. Click on **R2** in the sidebar
3. Your **Account ID** is shown at the top of the page

### 2. Create an R2 Bucket
1. In R2 dashboard, click **Create bucket**
2. Enter a bucket name (e.g., `abs-resources`)
3. Click **Create bucket**
4. Note down your **Bucket Name**

### 3. Create API Token
1. In R2 dashboard, go to **Manage R2 API Tokens**
2. Click **Create API token**
3. Give it a name (e.g., `abs-uploads`)
4. Permissions: **Object Read & Write**
5. Click **Create API Token**
6. **IMPORTANT**: Copy both:
   - **Access Key ID**
   - **Secret Access Key**
   (You won't see the secret again!)

### 4. Get Your R2 Endpoint
Your R2 endpoint URL format is:
```
https://<ACCOUNT_ID>.r2.cloudflarestorage.com
```

Replace `<ACCOUNT_ID>` with your actual Account ID from step 1.

### 5. Configure Public Access (Optional)
If you want files to be publicly accessible:
1. In your bucket settings, go to **Settings**
2. Under **Public access**, click **Connect Domain** or **Allow Access**
3. Set up a custom domain or use R2's public URL

Your public URL will be something like:
```
https://pub-<hash>.r2.dev
```

Or your custom domain:
```
https://files.yourdomain.com
```

## Environment Variables

Add these to your `.env` file and Vercel environment variables:

```env
# Cloudflare R2 Storage
R2_ACCESS_KEY_ID=your_access_key_id_here
R2_SECRET_ACCESS_KEY=your_secret_access_key_here
R2_BUCKET=abs-resources
R2_ENDPOINT=https://your-account-id.r2.cloudflarestorage.com
R2_PUBLIC_URL=https://pub-hash.r2.dev  # or your custom domain
```

## Add to Vercel

1. Go to your [Vercel project settings](https://vercel.com/dashboard)
2. Navigate to **Settings** → **Environment Variables**
3. Add each R2 variable:
   - `R2_ACCESS_KEY_ID`
   - `R2_SECRET_ACCESS_KEY`
   - `R2_BUCKET`
   - `R2_ENDPOINT`
   - `R2_PUBLIC_URL`
4. Click **Save**
5. Redeploy your application

## Testing

After deployment, try uploading a PDF file through the teacher resources interface. The file should now be stored in your R2 bucket and accessible via the public URL.

## Pricing

- **10 GB storage** - Free
- **1 million Class A operations/month** - Free (uploads, lists)
- **10 million Class B operations/month** - Free (downloads)

Perfect for your student management system!

## Troubleshooting

### Files not uploading
- Check that all environment variables are set correctly
- Verify API token has **Object Read & Write** permissions
- Ensure bucket name matches exactly

### Files not accessible
- Make sure bucket has public access enabled
- Check that `R2_PUBLIC_URL` is set correctly
- Verify CORS settings if accessing from browser

### "Access Denied" errors
- Regenerate API token with correct permissions
- Double-check Access Key ID and Secret Access Key are correct
