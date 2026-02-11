<?php

namespace App\Console\Commands;

use App\Jobs\PublishPostJob;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PostScheduledJobs extends Command
{
    protected $signature = 'app:post-scheduled-jobs';

    protected $description = 'Post scheduled jobs!';

    public function handle()
    {
        $posts = Post::select('id')
            ->whereNull('published_at')
            ->whereBetween('scheduled_at', [
                Carbon::now()->subMinutes(1)->format('Y-m-d H:i:s'),
                Carbon::now()->addMinutes(1)->format('Y-m-d H:i:s'),
            ])
            ->get();

        $this->info('Posts count: '.$posts->count());

        foreach ($posts as $post) {
            PublishPostJob::dispatch($post->id)->onQueue('high');
        }
    }
}
