<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\FacebookService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PublishPostJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private $postId
    ) {}

    public function handle(): void
    {

        $post = Post::with('page')->find($this->postId);

        $service = new FacebookService(
            $post->page->access_token
        );

        $service->createPost($post);
    }
}
