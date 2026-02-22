<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WhatsAppService
{
    /**
     * ارسال اعلان معامله از طریق WhatsApp Template
     *
     * @param string $phone شماره تلفن مشتری
     * @param array  $data  داده‌های تراکنش (customer_name, exchange_name, account_number, amount, currency, transaction_type, transaction_date, balance, exchange_contact)
     * @return bool
     */
    public static function sendTransaction(string $phone, array $data): bool
    {
        // پاکسازی شماره: فقط اعداد باقی می‌ماند
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // اگر شماره خالی شد، ارسال نکن
        if (empty($phone)) {
            Log::warning('WhatsApp: phone number is empty');
            return false;
        }

        // تبدیل شماره به فرمت بین‌المللی (مثلاً افغانستان +93)
        if (substr($phone, 0, 2) === '93') {
            $phone = '+' . $phone;
        }

        // پارامترهای Template واتساپ (همه string)
        $parameters = [
            ["type" => "text", "text" => (string) ($data['customer_name'] ?? '-')],        // {{1}} نام مشتری
            ["type" => "text", "text" => (string) ($data['exchange_name'] ?? '-')],        // {{2}} نام صرافی
            ["type" => "text", "text" => (string) ($data['account_number'] ?? '-')],       // {{3}} شماره حساب
            ["type" => "text", "text" => (string) ($data['amount'] ?? '-')],               // {{4}} مبلغ
            ["type" => "text", "text" => (string) ($data['currency'] ?? '-')],             // {{5}} ارز
            ["type" => "text", "text" => (string) ($data['transaction_date'] ?? '-')],     // {{6}} تاریخ تراکنش
            ["type" => "text", "text" => (string) ($data['transaction_type'] ?? '-')],     // {{7}} نوع تراکنش
            ["type" => "text", "text" => (string) ($data['balance'] ?? '-')],              // {{8}} موجودی فعلی
            ["type" => "text", "text" => (string) ($data['currency'] ?? '-')],             // {{9}} ارز موجودی
            ["type" => "text", "text" => (string) ($data['exchange_contact'] ?? '-')],     // {{10}} شماره تماس صرافی
        ];

        // Payload نهایی برای API واتساپ
        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $phone,
            "type" => "template",
            "template" => [
                "name" => "cashtrans_fa",
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
            // ارسال درخواست به WhatsApp API
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

            // بررسی موفقیت درخواست
            if (!$response->successful()) {
                Log::error('WhatsApp API error', [
                    'status'  => $response->status(),
                    'body'    => $response->body(),
                    'payload' => $payload,
                ]);
                return false;
            }

            // موفقیت ارسال پیام
            Log::info('WhatsApp message sent successfully', [
                'phone'      => $phone,
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
