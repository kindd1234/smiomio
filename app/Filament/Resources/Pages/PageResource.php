<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Pages\ManagePages;
use App\Models\Account;
use App\Models\Page;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Page';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->default_workspace_id !== null;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('remote_id'),
                TextEntry::make('name'),
                TextEntry::make('profile_pic'),
                TextEntry::make('page_creation_time')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $accounts = Account::select()->get()->pluck('id')->toArray();

        return $table
            // ->modifyQueryUsing(function ($query) use ($accounts) {
            //     return $query->whereIn('account_id', $accounts);
            // })
            ->recordTitleAttribute('Page')
            ->columns([
                TextColumn::make('id')->label('#'),
                ImageColumn::make('profile_pic')
                    ->label('Profile')
                    ->width(50)
                    ->height(50)
                    ->extraAttributes(['style' => 'object-fit: cover;']),

                TextColumn::make('name')
                    ->label('Name')
                    ->formatStateUsing(function ($state, $record) {
                        return "<a href='https://facebook.com/{$record->remote_id}' target='_blank'>{$state}</a>";
                    })
                    ->sortable()
                    ->html(),

                TextColumn::make('parentPage.name')
                    ->label('Parent')
                    ->searchable(),

                TextInputColumn::make('backed_time')
                    ->label('Back time')
                    ->rules(['required', 'date'])
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->backed_time
                            ? \Carbon\Carbon::parse($record->backed_time)->format('d/m/Y')
                            : null
                    )
                    ->updateStateUsing(function ($state, $record) {
                        if ($state) {
                            $record->update([
                                'backed_time' => Carbon::createFromFormat('d/m/Y', $state)->format('Y-m-d'),
                            ]);
                        }
                    }),
                TextColumn::make('has_valid_token')
                    ->label('Token Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Valid' : 'Invalid')
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('token_validity_checked_at')
                    ->label('Last Checked')
                    ->dateTime('M d, Y H:i')
                    ->since()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Action::make('check_token_validity')
                    ->label('Check Token')
                    ->color('warning')
                    ->action(function ($record) {

                        $response = Http::get('https://graph.facebook.com/debug_token', [
                            'input_token' => $record->access_token,
                            'access_token' => env('FB_CLIENT_ID').'|'.env('FB_SECRET_ID'),
                        ]);

                        Log::error('Check Token', $response->json());

                        if ($response->successful()) {
                            $isValid = $response->json('data.is_valid') === true;
                            $message = $isValid ? 'Valid' : 'Invalid';

                            $notification = Notification::make()
                                ->title('Checked')
                                ->body($message.' Token');

                            $record->update([
                                'has_valid_token' => $isValid,
                                'token_validity_checked_at' => Carbon::now(),
                            ]);

                            if ($isValid) {
                                $notification = $notification->success();
                            } else {
                                $notification = $notification->danger();
                            }
                            $notification->send();
                        } else {
                            $notification = Notification::make()
                                ->title('Unsuccessful check')
                                ->body('Something went wrong, please try again later!')
                                ->send();
                        }
                    })
                    ->tooltip('Check if the access token is valid'),
                ViewAction::make('view_page')
                    ->label('Visit')
                    ->url(fn ($record) => "https://facebook.com/{$record->remote_id}")
                    ->openUrlInNewTab(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePages::route('/'),
        ];
    }
}
