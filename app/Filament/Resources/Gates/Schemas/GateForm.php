<?php

namespace App\Filament\Resources\Gates\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('door_model_id')
                ->label('Модель двери')
                ->relationship('doorModel', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('category_id')
                ->label('Категория')
                ->relationship('category', 'name')
                ->searchable()
                ->preload(),

            Select::make('leaf_type')
                ->label('Тип по створкам')
                ->options([
                    'single' => 'Одностворчатая',
                    'double' => 'Двустворчатая',
                    'triple' => 'Трёхстворчатая',
                ])
                ->placeholder('Не указан')
                ->required(),

            TextInput::make('name')
                ->label('Название')
                ->required(),

            FileUpload::make('image')
                ->label('Фото двери (PNG/JPG)')
                ->image()
                ->directory('gates')
                ->disk('public')
                ->acceptedFileTypes(['image/png', 'image/jpeg'])
                ->maxSize(10240)
                ->required(),

            Textarea::make('description')
                ->label('Описание')
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label('Активна')
                ->default(true),

            TextInput::make('sort_order')
                ->label('Порядок сортировки')
                ->numeric()
                ->default(0),
        ]);
    }
}
