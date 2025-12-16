<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UploadThingService
{
    protected string $apiKey;
    protected string $appId;

    public function __construct()
    {
        $this->apiKey = config('services.uploadthing.api_key');
        $this->appId = config('services.uploadthing.app_id');
    }

    /**
     * Upload a file to UploadThing
     */
    public function upload(UploadedFile $file, string $folder = 'resources'): array
    {
        // Generate a unique filename
        $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

        // Get presigned URL from UploadThing
        $response = Http::withHeaders([
            'X-Uploadthing-Api-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.uploadthing.com/v6/uploadFiles', [
            'files' => [
                [
                    'name' => $fileName,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                ]
            ],
            'metadata' => [
                'folder' => $folder,
            ],
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to get upload URL: ' . $response->body());
        }

        $uploadData = $response->json();
        $presignedUrl = $uploadData['data'][0]['url'] ?? null;
        $key = $uploadData['data'][0]['key'] ?? null;

        if (!$presignedUrl || !$key) {
            throw new \Exception('Invalid response from UploadThing');
        }

        // Upload the file to the presigned URL
        $uploadResponse = Http::attach(
            'file',
            file_get_contents($file->getRealPath()),
            $fileName
        )->post($presignedUrl);

        if (!$uploadResponse->successful()) {
            throw new \Exception('Failed to upload file: ' . $uploadResponse->body());
        }

        // Return file information
        return [
            'key' => $key,
            'url' => "https://utfs.io/f/{$key}",
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'type' => $file->getClientOriginalExtension(),
        ];
    }

    /**
     * Delete a file from UploadThing
     */
    public function delete(string $key): bool
    {
        $response = Http::withHeaders([
            'X-Uploadthing-Api-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.uploadthing.com/v6/deleteFiles', [
            'fileKeys' => [$key],
        ]);

        return $response->successful();
    }

    /**
     * Get file URL from key
     */
    public function getUrl(string $key): string
    {
        return "https://utfs.io/f/{$key}";
    }
}
