<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadThingService
{
    /**
     * Upload a file to Cloudflare R2
     */
    public function upload(UploadedFile $file, string $folder = 'resources'): array
    {
        // Generate unique filename
        $filename = Str::uuid() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' . $filename;

        // Upload to R2
        Storage::disk('r2')->put($path, file_get_contents($file->getRealPath()), 'public');

        // Get the public URL
        $url = Storage::disk('r2')->url($path);

        // Return file information
        return [
            'key' => $path,
            'url' => $url,
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'type' => $file->getClientOriginalExtension(),
        ];
    }

    /**
     * Delete a file from R2
     */
    public function delete(string $path): bool
    {
        return Storage::disk('r2')->delete($path);
    }

    /**
     * Get file URL from path
     */
    public function getUrl(string $path): string
    {
        return Storage::disk('r2')->url($path);
    }
}


