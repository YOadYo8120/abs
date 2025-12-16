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
        // Generate a unique filename and custom ID
        $customId = Str::uuid()->toString();
        $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

        // Step 1: Request presigned POST data
        $response = Http::withHeaders([
            'X-Uploadthing-Api-Key' => $this->apiKey,
        ])->post('https://api.uploadthing.com/v6/requestFileAccess', [
            'files' => [
                [
                    'name' => $fileName,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                    'customId' => $customId,
                ],
            ],
            'metadata' => [
                'folder' => $folder,
            ],
            'acl' => 'public-read',
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to get upload URL: ' . $response->body());
        }

        $uploadData = $response->json();
        $fileData = $uploadData['data'][0] ?? null;

        if (!$fileData) {
            throw new \Exception('Invalid response from UploadThing: ' . json_encode($uploadData));
        }

        // Step 2: Upload to S3 using presigned POST
        $fields = $fileData['fields'] ?? [];
        $uploadUrl = $fileData['url'] ?? null;
        $key = $fileData['key'] ?? null;

        if (!$uploadUrl || !$key) {
            throw new \Exception('Missing upload URL or key in response');
        }

        // Prepare multipart form data for S3
        $multipart = [];
        foreach ($fields as $fieldKey => $fieldValue) {
            $multipart[] = [
                'name' => $fieldKey,
                'contents' => $fieldValue,
            ];
        }

        // Add the file
        $multipart[] = [
            'name' => 'file',
            'contents' => fopen($file->getRealPath(), 'r'),
            'filename' => $fileName,
        ];

        // Upload to S3
        $uploadResponse = Http::asMultipart()->post($uploadUrl, $multipart);

        if (!$uploadResponse->successful() && $uploadResponse->status() !== 204) {
            throw new \Exception('Failed to upload file to storage: ' . $uploadResponse->body());
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
