<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UploadThingService
{
    /**
     * Upload a file - stores in database temporarily
     * TODO: Move to Cloudflare R2 or AWS S3 for production
     */
    public function upload(UploadedFile $file, string $folder = 'resources'): array
    {
        // Generate unique key for the file
        $key = Str::uuid() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

        // Read file content and encode it
        $fileContent = base64_encode(file_get_contents($file->getRealPath()));

        // Return file information
        return [
            'key' => $key,
            'url' => url("/resources/download/{$key}"),
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'type' => $file->getClientOriginalExtension(),
            'file_content' => $fileContent, // This will be stored in database
        ];
    }
}

