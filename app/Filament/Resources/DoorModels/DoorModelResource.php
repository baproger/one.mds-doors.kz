<?php

namespace App\Filament\Resources\DoorModels;

use App\Filament\Resources\DoorModels\Pages\EditDoorModel;
use App\Filament\Resources\DoorModels\Pages\ListDoorModels;
use App\Models\DoorModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class DoorModelResource extends Resource
{
    protected static ?string $model = DoorModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Модели дверей';

    protected static ?string $modelLabel = 'Модель';

    protected static ?string $pluralModelLabel = 'Модели дверей';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Название модели')
                ->placeholder('Горизонт, Бастион, Армада...')
                ->required(),

            FileUpload::make('image')
                ->label('Фото модели (PNG) — общий вид')
                ->image()
                ->directory('door-models')
                ->disk('public')
                ->acceptedFileTypes(['image/png', 'image/jpeg'])
                ->maxSize(10240),

            Textarea::make('description')
                ->label('Описание')
                ->columnSpanFull(),

            TextInput::make('sort_order')
                ->label('Порядок сортировки')
                ->numeric()
                ->default(0),

            Toggle::make('is_active')
                ->label('Активна')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->label('Фото'),
                TextColumn::make('name')
                    ->label('Модель')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gates_count')
                    ->label('Конфигураций')
                    ->counts('gates')
                    ->badge()
                    ->color('info'),
                TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDoorModels::route('/'),
            'edit'  => EditDoorModel::route('/{record}/edit'),
        ];
    }
}
