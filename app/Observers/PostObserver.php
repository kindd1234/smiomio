<?php

namespace App\Observers;

use App\Jobs\PublishPostJob;
use App\Models\Post;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    public function created(Post $post)
    {

        if (! empty($post->scheduled_at)) {
            PublishPostJob::dispatch($post->id)
                ->delay(\Carbon\Carbon::parse($post->scheduled_at))
                ->onQueue('high');

            Log::error('Post scheduled at: '.$post->scheduled_at);

            return;
        }

        PublishPostJob::dispatch($post->id)->onQueue('high');

    }
}
