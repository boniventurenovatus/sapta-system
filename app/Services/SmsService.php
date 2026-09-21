<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Tuma SMS kwa namba ya simu
     */
    public static function send(string $phone, string $message): bool
    {
        try {
            // Safisha namba ya simu
            $phone = preg_replace('/[^0-9]/', '', $phone);
            
            // Kama namba inaanza na 0, badilisha kuwa 255
            if (substr($phone, 0, 1) === '0') {
                $phone = '255' . substr($phone, 1);
            }
            
            // Kama namba inaanza na +, ondoa
            if (substr($phone, 0, 1) === '+') {
                $phone = substr($phone, 1);
            }

            // Beem Africa (Tanzania)
            $apiKey = config('services.beem.api_key');
            $secretKey = config('services.beem.secret_key');
            $senderId = config('services.beem.sender_id', 'SAPTA');

            if ($apiKey && $secretKey) {
                $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($apiKey . ':' . $secretKey),
                    'Content-Type' => 'application/json',
                ])->post('https://apisms.beem.africa/v1/send', [
                    'source_addr' => $senderId,
                    'schedule_time' => '',
                    'encoding' => 0,
                    'message' => $message,
                    'recipients' => [
                        ['recipient_id' => 1, 'dest_addr' => $phone],
                    ],
                ]);

                if ($response->successful()) {
                    Log::info('SMS sent successfully', ['phone' => $phone]);
                    return true;
                }

                Log::error('SMS failed', ['phone' => $phone, 'response' => $response->body()]);
                return false;
            }

            // Kama hakuna config, tumia log
            Log::info('SMS (no config): ' . $message, ['phone' => $phone]);
            return true;

        } catch (\Exception $e) {
            Log::error('SMS error: ' . $e->getMessage());
            return false;
        }
    }
}