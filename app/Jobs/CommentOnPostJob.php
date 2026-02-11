<?php

namespace App\Jobs;

use App\Services\FacebookService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CommentOnPostJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private $token,
        private $postId,
        private $comment,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $facebookService = new FacebookService;

        $facebookService->commentOnPost(
            $this->token,
            $this->postId,
            $this->comment
        );
    }
}
