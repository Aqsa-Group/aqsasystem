<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * ارسال اعلان معامله از طریق WhatsApp Template
     */
    public static function sendTransaction(string $phone, array $data): bool
    {
        // پاکسازی شماره (فقط عدد)
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // اگر شماره خالی شد، ارسال نکن
        if (empty($phone)) {
            Log::warning('WhatsApp: phone number is empty');
            return false;
        }

        // آماده‌سازی payload
        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $phone,
            "type" => "template",
            "template" => [
                "name" => "aqsasystem_en",
                "language" => [
                    "code" => "en"
                ],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => [
                            ["type" => "text", "text" => $data['account_number'] ?? '-'],
                            ["type" => "text", "text" => $data['amount'] ?? '-'],
                            ["type" => "text", "text" => $data['currency'] ?? '-'],
                            ["type" => "text", "text" => $data['transaction_type'] ?? '-'],
                            ["type" => "text", "text" => $data['transaction_date'] ?? '-'],
                        ]
                    ]
                ]

            ]
        ];

        try {
            $response = Http::withToken(config('services.whatsapp.token'))
                ->timeout(10)
                ->post(
                    "https://graph.facebook.com/v18.0/" .
                        config('services.whatsapp.phone_id') .
                        "/messages",
                    $payload
                );

           


            // اگر موفق نبود
            if (!$response->successful()) {
                Log::error('WhatsApp API error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'payload' => $payload,
                ]);
                return false;
            }

            // موفق
            Log::info('WhatsApp message sent', [
                'phone' => $phone,
                'response' => $response->json(),
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::critical('WhatsApp exception', [
                'message' => $e->getMessage(),
                'phone' => $phone,
            ]);
            return false;
        }
    }
}
