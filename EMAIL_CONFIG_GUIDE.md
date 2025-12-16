# Email Configuration for Production

## Issue
Email notifications for student and teacher creation are redirecting to `localhost:8000` instead of your production Vercel URL.

## Solution Implemented
Updated both notification files to use `config('app.url')` instead of hardcoded URLs:
- `app/Notifications/TeacherPasswordNotification.php`
- `app/Notifications/StudentPasswordNotification.php`

## What You Need to Do

### Step 1: Find Your Vercel Deployment URL
Your app is deployed at a URL like:
- `https://abs.vercel.app` OR
- `https://abs-[your-project-name].vercel.app`

To find it:
1. Go to https://vercel.com/dashboard
2. Click on your `abs` project
3. Copy the production URL shown at the top

### Step 2: Set Environment Variable in Vercel

**Method A: Via Vercel Dashboard (Recommended)**
1. Go to your project in Vercel Dashboard
2. Click **Settings** → **Environment Variables**
3. Add a new variable:
   - **Key:** `APP_URL`
   - **Value:** `https://your-actual-vercel-url.vercel.app` (replace with your actual URL)
   - **Environment:** Select "Production"
4. Click **Save**
5. Go to **Deployments** tab and click **Redeploy** on the latest deployment

**Method B: Via Command Line (if you have Vercel CLI)**
```bash
cd /home/yoadyo/VScode/laravel/abs
vercel env add APP_URL production
# When prompted, enter your production URL: https://your-url.vercel.app
vercel --prod
```

### Step 3: Update Local .env (Optional - for local testing)
Update line 5 in your `.env` file:
```env
APP_URL=https://your-actual-vercel-url.vercel.app
```

## Verification
After redeploying, when you create a new teacher or student:
1. The email will be sent
2. The "Login to Your Account" button will redirect to your production Vercel URL
3. No more localhost redirects!

## Example
If your Vercel URL is `https://abs-yoadyo8120.vercel.app`, set:
```
APP_URL=https://abs-yoadyo8120.vercel.app
```

The email login button will then link to:
```
https://abs-yoadyo8120.vercel.app/login
```
