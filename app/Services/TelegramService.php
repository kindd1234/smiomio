<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    public function notify(string $message, array $buttons = [])
    {
        try {
            $url = 'https://api.telegram.org/bot'.env('TELEGRAM_BOT_TOKEN').'/sendMessage';

            $payload = [
                'chat_id' => env('TELEGRAM_CHAT_ID'),
                'text' => $message,
                'parse_mode' => 'HTML',
            ];

            if (! empty($buttons)) {
                $payload['reply_markup'] = json_encode(['inline_keyboard' => $buttons]);
            }

            $response = Http::post($url, $payload);

            Log::error('Telegram Response '.json_encode($response->json()));
        } catch (\Exception $exception) {
            Log::error('Telegram Error: '.$exception->getMessage());
        }

    }
}
