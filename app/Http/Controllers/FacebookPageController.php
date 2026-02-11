<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Page;
use App\Services\FacebookService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookPageController extends Controller
{
    public function handleFacebookCallback(Request $request)
    {
        Log::info('Facebook callback started', [
            'user_id' => Auth::id(),
            'workspace_id' => $request->user()->default_workspace_id,
        ]);

        $code = $request->input('code');
        Log::info('Facebook callback code received', ['code' => $code]);

        $service = new FacebookService;

        try {
            $userToken = $service->handleFacebookCallback($code);
            Log::info('Facebook user token received');
        } catch (\Throwable $e) {
            Log::error('Failed to get Facebook user token', [
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }

        $fbAccount = $service->getAccount($userToken);
        Log::info('Facebook account fetched', $fbAccount);

        $account = Account::updateOrCreate(
            [
                'remote_id' => $fbAccount['id'],
            ],
            [
                'workspace_id' => $request->user()->default_workspace_id,
                'user_id' => Auth::id(),
                'remote_id' => $fbAccount['id'],
                'name' => $fbAccount['name'],
            ]
        );

        Log::info('Account saved/updated', [
            'account_id' => $account->id,
            'remote_id' => $fbAccount['id'],
        ]);

        $pages = $service->fetchUserPages($userToken);
        Log::info('Facebook pages fetched', [
            'count' => count($pages),
        ]);

        foreach ($pages as $page) {
            Log::info('Processing page', [
                'page_id' => $page['id'],
                'page_name' => $page['name'],
            ]);

            $detailResponse = Http::get("https://graph.facebook.com/v24.0/{$page['id']}", [
                'access_token' => $page['access_token'],
                'fields' => 'id,name,picture{url},start_info',
            ]);

            if (! $detailResponse->successful()) {
                Log::error('Failed to fetch page details', [
                    'page_id' => $page['id'],
                    'response' => $detailResponse->body(),
                ]);
                continue;
            }

            $details = $detailResponse->json() ?? [];

            $profilePic = $details['picture']['data']['url'] ?? '';
            $createdTime = Carbon::now()->subMonths(12)->format('Y-m-d');

            $parent = Page::updateOrCreate(
                [
                    'remote_id' => $page['id'],
                    'workspace_id' => $request->user()->default_workspace_id,
                ],
                [
                    'backed_time' => '2020-10-10',
                    'account_id' => $account->id,
                    'name' => $page['name'],
                    'profile_pic' => $profilePic,
                    'access_token' => $page['access_token'],
                    'page_creation_time' => $createdTime,
                ]
            );

            Log::info('Parent page saved/updated', [
                'page_id' => $parent->id,
                'remote_id' => $page['id'],
            ]);

            $childrensResponse = Http::get(
                "https://graph.facebook.com/v24.0/{$page['id']}/locations",
                [
                    'fields' => 'id,name,access_token,picture{url}',
                    'access_token' => $page['access_token'],
                ]
            );

            $childrens = $childrensResponse->json();

            if (! empty($childrens['data'])) {
                Log::info('Child pages found', [
                    'parent_remote_id' => $page['id'],
                    'count' => count($childrens['data']),
                ]);

                foreach ($childrens['data'] as $children) {
                    $child = Page::updateOrCreate(
                        [
                            'remote_id' => $children['id'],
                            'workspace_id' => $request->user()->default_workspace_id,
                        ],
                        [
                            'parent_id' => $parent->id,
                            'account_id' => $account->id,
                            'backed_time' => '2020-10-10',
                            'name' => $children['name'],
                            'profile_pic' => $children['picture']['data']['url'] ?? '',
                            'access_token' => $children['access_token'],
                            'page_creation_time' => $createdTime,
                        ]
                    );

                    Log::info('Child page saved/updated', [
                        'child_page_id' => $child->id,
                        'remote_id' => $children['id'],
                    ]);
                }
            } else {
                Log::info('No child pages found', [
                    'parent_remote_id' => $page['id'],
                ]);
            }
        }

        Log::info('Facebook callback finished successfully');

        return redirect('/admin/pages');
    }
}
