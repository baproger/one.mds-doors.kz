<?php

namespace App\Filament\Resources\Gates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->label('Фото'),
                TextColumn::make('doorModel.name')
                    ->label('Модель')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('leaf_type')
                    ->label('Створки')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'single' => 'success',
                        'double' => 'info',
                        'triple' => 'warning',
                        default  => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'single' => 'Одностворчатая',
                        'double' => 'Двустворчатая',
                        'triple' => 'Трёхстворчатая',
                        default  => '—',
                    }),
                TextColumn::make('category.name')
                    ->label('Категория')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('leaf_type')
                    ->label('Створки')
                    ->options([
                        'single' => 'Одностворчатая',
                        'double' => 'Двустворчатая',
                        'triple' => 'Трёхстворчатая',
                    ])
                    ->placeholder('Все'),
                SelectFilter::make('category_id')
                    ->label('Категория')
                    ->relationship('category', 'name')
                    ->placeholder('Все категории'),
            ])
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
