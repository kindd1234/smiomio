<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePages extends ManageRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Connect Pages')
                ->url($this->redirectToFacebookLink())
                ->openUrlInNewTab(),
        ];
    }

    public function redirectToFacebookLink()
    {
        $appId = env('FB_CLIENT_ID');
        $redirectUri = env('FB_CALLBACK');

        $scopes = implode(',', [
            'pages_show_list',
            'pages_read_engagement',
            'pages_manage_posts',
            'pages_manage_engagement',
            'business_management',
        ]);

        $state = csrf_token();

        $url = 'https://www.facebook.com/v24.0/dialog/oauth'.
            "?client_id={$appId}".
            "&redirect_uri={$redirectUri}".
            "&state={$state}".
            "&scope={$scopes}".
            '&response_type=code';

        return $url;
    }
}
