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

        // Upload file using proper multipart/form-data format
        $response = Http::withHeaders([
            'X-Uploadthing-Api-Key' => $this->apiKey,
        ])->asMultipart()
            ->attach('files', file_get_contents($file->getRealPath()), $fileName)
            ->post('https://api.uploadthing.com/v6/uploadFiles');

        if (!$response->successful()) {
            throw new \Exception('Failed to upload file: ' . $response->body());
        }

        $result = $response->json();
        $fileData = $result['data'][0] ?? null;

        if (!$fileData) {
            throw new \Exception('Invalid response from UploadThing: ' . json_encode($result));
        }

        $key = $fileData['key'] ?? null;
        if (!$key) {
            throw new \Exception('No file key in response');
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
