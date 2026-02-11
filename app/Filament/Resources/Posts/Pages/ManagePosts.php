<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePosts extends ManageRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Post')
                ->using(function ($data, $record) {

                    unset($data['account_id']);

                    $pageIds = $data['page_id'];

                    unset($data['page_id']);

                    foreach ($pageIds as $pageId) {

                        $data['page_id'] = $pageId;
                        $data['name'] = ! empty($data['name']) ? $data['name'] : '';
                        $data['comment'] = ! empty($data['comment']) ? $data['comment'] : '';
                        if (isset($postData['image'])) {
                            $data['image'] = asset('storage/'.$data['image']);
                        }
                        if (empty($data['text_format_preset_id'])) {
                            $data['text_format_preset_id'] = '';
                        }
                        $data['status'] = ! empty($data['scheduled_at']) ? 'scheduled' : 'queued';

                        $record = $this->getResource()::getModel()::create(
                            $data
                        );
                    }

                    return $record;
                }),
        ];
    }
}
