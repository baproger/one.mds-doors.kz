<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_id')
                    ->label('Проект')
                    ->relationship('project', 'id')
                    ->nullable(),
                TextInput::make('name')
                    ->label('Имя')
                    ->required(),
                TextInput::make('phone')
                    ->label('Телефон')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email(),
                Textarea::make('message')
                    ->label('Сообщение')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Новый',
                        'in_progress' => 'В работе',
                        'done' => 'Завершён',
                        'rejected' => 'Отклонён',
                    ])
                    ->default('new')
                    ->required(),
            ]);
    }
}
