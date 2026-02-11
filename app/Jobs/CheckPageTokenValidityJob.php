<?php

namespace App\Jobs;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckPageTokenValidityJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected $pageId,
        protected $accessToken
    ) {}

    public function handle(): void
    {
        $page = Page::find($this->pageId);

        if (! $page) {
            Log::error('Page not found in CheckPageTokenValidityJob', [
                'page_id' => $this->pageId,
            ]);

            return;
        }

        $response = Http::get('https://graph.facebook.com/debug_token', [
            'input_token' => $this->accessToken,
            'access_token' => env('FB_CLIENT_ID').'|'.env('FB_SECRET_ID'),
        ]);

        Log::info('Checking token', $response->json());

        if ($response->successful()) {

            $isValid = $response->json('data.is_valid') === true ? 1 : 0;

            $page->update([
                'has_valid_token' => $isValid,
                'token_validity_checked_at' => Carbon::now(),
            ]);

        } else {
            Log::error('Token check failed', $response->json());
        }
    }
}
