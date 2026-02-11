<?php

namespace App\Filament\Resources\Workspaces;

use App\Filament\Resources\Workspaces\Pages\ManageWorkspaces;
use App\Models\Workspace;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkspaceResource extends Resource
{
    protected static ?string $model = Workspace::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Window;

    protected static ?string $recordTitleAttribute = 'Workspace';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Workspace')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->visible(fn ($record) => $record->id !== auth()->user()->default_workspace_id),

                Action::make('setCurrent')
                    ->label(fn ($record): string => $record->id === auth()->user()->default_workspace_id
                            ? 'Current Workspace'
                            : 'Switch'
                    )
                    ->action(function ($record) {
                        $user = auth()->user();
                        $user->default_workspace_id = $record->id;
                        $user->save();

                        Notification::make()
                            ->title('Workspace set as current.')
                            ->success()
                            ->send();
                    })
                    ->color(fn ($record) => $record->id === auth()->user()->default_workspace_id
                            ? 'success'
                            : 'primary'
                    )
                    ->disabled(fn ($record): bool => $record->id === auth()->user()->default_workspace_id
                    ),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageWorkspaces::route('/'),
        ];
    }
}
