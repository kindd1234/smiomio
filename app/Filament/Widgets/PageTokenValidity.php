<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class PageTokenValidity extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Page::query()->orderBy('has_valid_token', 'DESC'))
            ->columns([
                ImageColumn::make('profile_pic')
                    ->label('Profile')
                    ->rounded()
                    ->size(40)
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Page Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
