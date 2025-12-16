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
        // Step 1: Request presigned upload URLs from UploadThing
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $contentType = $file->getMimeType();

        $prepareResponse = Http::withHeaders([
            'X-Uploadthing-Api-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.uploadthing.com/v6/uploadFiles', [
            'files' => [
                [
                    'name' => $fileName,
                    'size' => $fileSize,
                    'type' => $contentType,
                ]
            ],
            'metadata' => [
                'folder' => $folder,
            ],
        ]);

        if (!$prepareResponse->successful()) {
            throw new \Exception('Failed to prepare upload: ' . $prepareResponse->body());
        }

        $prepareData = $prepareResponse->json();
        $uploadData = $prepareData['data'][0] ?? null;

        if (!$uploadData || empty($uploadData['url']) || empty($uploadData['key'])) {
            throw new \Exception('Invalid prepare response: ' . json_encode($prepareData));
        }

        // Step 2: Upload file content to presigned URL
        $uploadResponse = Http::withHeaders([
            'Content-Type' => $contentType,
        ])->withBody(file_get_contents($file->getRealPath()), $contentType)
            ->put($uploadData['url']);

        if (!$uploadResponse->successful()) {
            throw new \Exception('Failed to upload file to storage: ' . $uploadResponse->body());
        }

        // Return file information
        return [
            'key' => $uploadData['key'],
            'url' => "https://utfs.io/f/{$uploadData['key']}",
            'name' => $fileName,
            'size' => $fileSize,
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
