<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

        // پارامترهای قالب (همه string)
        $parameters = [
            ["type" => "text", "text" => (string) ($data['exchange_name'] ?? '-')],        // {{1}}
            ["type" => "text", "text" => (string) ($data['account_number'] ?? '-')],       // {{2}}
            ["type" => "text", "text" => (string) ($data['amount'] ?? '-')],               // {{3}}
            ["type" => "text", "text" => (string) ($data['currency'] ?? '-')],             // {{4}}
            ["type" => "text", "text" => (string) ($data['transaction_type'] ?? '-')],     // {{5}}
            ["type" => "text", "text" => (string) ($data['transaction_date'] ?? '-')],     // {{6}}
            ["type" => "text", "text" => (string) ($data['balance'] ?? '-')],              // {{7}}
            ["type" => "text", "text" => (string) ($data['exchange_contact'] ?? '-')],     // {{8}}
        ];

        // Payload نهایی
        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $phone,
            "type" => "template",
            "template" => [
                "name" => "transactions_fa",
                "language" => [
                    "code" => "fa"
                ],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => $parameters
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withToken(config('services.whatsapp.token'))
                ->timeout(15)
                ->post(
                    "https://graph.facebook.com/v22.0/" .
                    config('services.whatsapp.phone_id') .
                    "/messages",
                    $payload
                );

            // لاگ پاسخ کامل واتساپ
            Log::info('WhatsApp response', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            // اگر موفق نبود
            if (!$response->successful()) {
                Log::error('WhatsApp API error', [
                    'status'  => $response->status(),
                    'body'    => $response->body(),
                    'payload' => $payload,
                ]);
                return false;
            }

            // موفق
            Log::info('WhatsApp message sent successfully', [
                'phone'    => $phone,
                'message_id' => data_get($response->json(), 'messages.0.id'),
            ]);

            return true;

        } catch (\Throwable $e) {
            Log::critical('WhatsApp exception', [
                'message' => $e->getMessage(),
                'phone'   => $phone,
            ]);
            return false;
        }
    }
}
