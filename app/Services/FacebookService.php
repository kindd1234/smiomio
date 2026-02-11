<?php

namespace App\Services;

use App\Jobs\CommentOnPostJob;
use App\Models\Post;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookService
{
    private $call;

    public function __construct(
        private string $accessToken = ''
    ) {
        $this->call = Http::baseUrl('https://graph.facebook.com/v24.0/');
    }

    public function getAccount($token)
    {

        $response = $this->call->withToken($token)->get('/me');

        return $response->json();
    }

    public function createPost(Post $post)
    {
        $token = $post->page->access_token;
        $pageId = $post->page->remote_id;

        Log::error('Create Post Preset', [
            'token' => $token,
            'page_id' => $pageId,
        ]);

        if ($post->type === 'image') {

            $photoPayload = [
                'name' => $post->name,
                'url' => asset('storage/'.$post->image),
                'published' => false,
                'backdated_time' => $post->page->backed_time,
            ];

            $photoUpload = $this->call
                ->withToken($token)
                ->post("$pageId/photos", $photoPayload);

            Log::error('Create Photo Payload', $photoPayload);
            Log::error('Create Photo Response', $photoUpload->json());

            if (! isset($photoUpload['id'])) {
                throw new \Exception('Failed to upload photo to Facebook.');
            }

            $photoId = $photoUpload['id'];

            $feedPayload = [
                'attached_media' => [
                    ['media_fbid' => $photoId],
                ],
                'message' => $post->name,
                'published' => true,
            ];

            $createdPost = $this->call->withToken($token)
                ->post("$pageId/feed", $feedPayload);

            Log::error('Create Post Payload', $feedPayload);
            Log::error('Create Post Response', $createdPost->json());

            if (! isset($createdPost['id'])) {

                $post->update([
                    'status' => 'failed',
                    'response_error' => json_encode($createdPost->json()),
                ]);

                return;
            }

            $postId = $createdPost['id'];
        } else {

            $payload = [
                'message' => $post->name,
                'text_format_preset_id' => $post->text_format_preset_id,
                'published' => true,
            ];

            $createdPost = $this->call->withToken($token)
                ->post("$pageId/feed", $payload);

            Log::error('Create Text Post Payload', $payload); 
            Log::error('Created Text Post Response', $createdPost->json());

            if (! isset($createdPost['id'])) {
                $post->update([
                    'status' => 'failed',
                    'response_error' => json_encode($createdPost->json()),
                ]);

                return;
            }

            $postId = $createdPost['id'];
        }
        $hidePayload = [
				'timeline_visibility' => 'hidden',
		];

        $hidden = $this->call->withToken($token)
            ->post("$postId", $hidePayload);

        Log::error('Hide Post', [
            'payload' => $hidePayload,
            'response' => $hidden->json(),
        ]);

        if (! empty($post->comment)) {
            $delayComment = (int) ($post->delay_comment ?? 0);

            CommentOnPostJob::dispatch(
                $token,
                $postId,
                $post->comment,
            )
                ->onQueue('high')
                ->delay(now()->addSeconds($delayComment));

        }

        $post->update([
            'remote_id' => $postId,
            'status' => 'published',
            'visibility' => 'hidden',
            'published_at' => now(),
        ]);
    }

    public function commentOnPost($token, $postId, $comment)
    {
        $commentPayload = [
            'message' => $comment,
        ];

        $comment = $this->call->withToken($token)
            ->post("$postId/comments", $commentPayload);

        Log::error('Adding Comment To Post', [
            'payload' => $commentPayload,
            'response' => $comment->json(),
        ]);
    }

    public function getPostInsights(Post $post)
    {
        $fields = 'id,likes.summary(true),comments.summary(true),shares,attachments{media,type,url}';

        $response = $this->call
            ->withToken($post->page->access_token)
            ->get($post->remote_id.'?fields='.$fields);

        $insights = $response->json();

        Log::error('Post Insights for '.$post->remote_id, [
            'response' => $insights,
        ]);

        return $insights;
    }

    public function handleFacebookCallback($code)
    {

        $queryString = http_build_query([
            'client_id' => config('facebook.FB_CLIENT_ID'),
            'client_secret' => config('facebook.FB_SECRET_ID'),
            'redirect_uri' => config('facebook.FB_CALLBACK'),
            'code' => $code,
        ]);

        $response = $this->call->get('oauth/access_token?'.$queryString);

        return $response->json('access_token');
    }

    public function fetchUserPages(string $userToken)
    {
        $response = Http::get('https://graph.facebook.com/v24.0/me/accounts', [
            'access_token' => $userToken,
            'fields' => 'id,name,access_token',
        ]);

        return $response->json('data') ?? [];
    }
}
