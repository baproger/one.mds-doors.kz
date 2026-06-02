<?php

namespace App\Filament\Resources\Leads;

use App\Filament\Resources\Leads\Pages\CreateLead;
use App\Filament\Resources\Leads\Pages\EditLead;
use App\Filament\Resources\Leads\Pages\ListLeads;
use App\Filament\Resources\Leads\Pages\ViewLead;
use App\Filament\Resources\Leads\Schemas\LeadForm;
use App\Filament\Resources\Leads\Tables\LeadsTable;
use App\Models\Lead;
use BackedEnum;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Заявки';

    protected static ?string $modelLabel = 'Заявка';

    protected static ?string $pluralModelLabel = 'Заявки';

    public static function form(Schema $schema): Schema
    {
        return LeadForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Контакт')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')->label('Имя'),
                        TextEntry::make('phone')->label('Телефон'),
                        TextEntry::make('email')->label('Email')->placeholder('—'),
                        TextEntry::make('status')
                            ->label('Статус')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'new' => 'warning',
                                'in_progress' => 'info',
                                'done' => 'success',
                                'rejected' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'new' => 'Новый',
                                'in_progress' => 'В работе',
                                'done' => 'Завершён',
                                'rejected' => 'Отклонён',
                                default => $state,
                            }),
                        TextEntry::make('message')->label('Сообщение')->placeholder('—')->columnSpanFull(),
                        TextEntry::make('created_at')->label('Дата заявки')->dateTime('d.m.Y H:i'),
                    ]),

                Section::make('Фото клиента')
                    ->columnSpanFull()
                    ->schema([
                        ImageEntry::make('project.source_image')
                            ->label('Загруженное фото дома')
                            ->disk('public')
                            ->height(320)
                            ->placeholder('Фото не загружено')
                            ->columnSpanFull(),
                        ImageEntry::make('project.result_image')
                            ->label('Результат визуализации')
                            ->disk('public')
                            ->height(320)
                            ->placeholder('Визуализация не сохранена')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Lead $record) => $record->project_id !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return LeadsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeads::route('/'),
            'create' => CreateLead::route('/create'),
            'view' => ViewLead::route('/{record}'),
            'edit' => EditLead::route('/{record}/edit'),
        ];
    }
}
