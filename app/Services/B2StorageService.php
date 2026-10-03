<?php

namespace App\Services;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class B2StorageService
{
    protected S3Client $client;
    protected string $bucket;

    public function __construct()
    {
        $this->bucket = env('B2_BUCKET_NAME');

        $this->client = new S3Client([
            'version' => 'latest',
            'region' => env('B2_REGION', 'eu-central-003'),
            'endpoint' => env('B2_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key' => env('B2_KEY_ID'),
                'secret' => env('B2_APPLICATION_KEY'),
            ],
        ]);
    }

    /**
     * Upload file contents
     */
    public function put(string $path, string $contents, string $mimeType = null): bool
    {
        try {
            $params = [
                'Bucket' => $this->bucket,
                'Key' => $path,
                'Body' => $contents,
            ];

            if ($mimeType) {
                $params['ContentType'] = $mimeType;
            }

            $this->client->putObject($params);
            return true;
        } catch (AwsException $e) {
            \Log::error('B2 put error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Upload file from path
     */
    public function putFile(string $path, string $localPath, string $mimeType = null): bool
    {
        try {
            $params = [
                'Bucket' => $this->bucket,
                'Key' => $path,
                'SourceFile' => $localPath,
            ];

            if ($mimeType) {
                $params['ContentType'] = $mimeType;
            }

            $this->client->putObject($params);
            return true;
        } catch (AwsException $e) {
            \Log::error('B2 putFile error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get file contents
     */
    public function get(string $path): ?string
    {
        try {
            $result = $this->client->getObject([
                'Bucket' => $this->bucket,
                'Key' => $path,
            ]);

            return (string) $result['Body'];
        } catch (AwsException $e) {
            \Log::error('B2 get error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete file
     */
    public function delete(string $path): bool
    {
        try {
            $this->client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $path,
            ]);
            return true;
        } catch (AwsException $e) {
            \Log::error('B2 delete error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if file exists
     */
    public function exists(string $path): bool
    {
        try {
            $this->client->headObject([
                'Bucket' => $this->bucket,
                'Key' => $path,
            ]);
            return true;
        } catch (AwsException $e) {
            return false;
        }
    }

    /**
     * Get temporary signed URL
     */
    public function temporaryUrl(string $path, int $minutes = 30): ?string
    {
        try {
            $cmd = $this->client->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key' => $path,
            ]);

            $request = $this->client->createPresignedRequest($cmd, "+{$minutes} minutes");
            return (string) $request->getUri();
        } catch (AwsException $e) {
            \Log::error('B2 temporaryUrl error: ' . $e->getMessage());
            return null;
        }
    }
}