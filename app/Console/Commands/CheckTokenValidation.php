<?php

namespace App\Console\Commands;

use App\Jobs\CheckPageTokenValidityJob;
use App\Models\Page;
use Illuminate\Console\Command;

class CheckTokenValidation extends Command
{
    protected $signature = 'app:check-token-validation';

    protected $description = '';

    public function handle()
    {

        $pages = Page::all();

        echo 'Checking tokens for '.$pages->count()." pages \n\n";

        $seconds = 1;

        foreach ($pages as $page) {
            echo 'Checking '.$page->id.' after '.$seconds." seconds\n\n";

            CheckPageTokenValidityJob::dispatch($page->id, $page->access_token)
                ->onQueue('default')
                ->delay(now()->addSeconds($seconds));

            $seconds = $seconds + 10;
        }

    }
}
