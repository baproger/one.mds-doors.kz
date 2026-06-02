<?php

namespace App\Filament\Resources\GateCategories;

use App\Filament\Resources\GateCategories\Pages\CreateGateCategory;
use App\Filament\Resources\GateCategories\Pages\EditGateCategory;
use App\Filament\Resources\GateCategories\Pages\ListGateCategories;
use App\Filament\Resources\GateCategories\Schemas\GateCategoryForm;
use App\Filament\Resources\GateCategories\Tables\GateCategoriesTable;
use App\Models\GateCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GateCategoryResource extends Resource
{
    protected static ?string $model = GateCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Категории';

    protected static ?string $modelLabel = 'Категория';

    protected static ?string $pluralModelLabel = 'Категории дверей';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return GateCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GateCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGateCategories::route('/'),
            'create' => CreateGateCategory::route('/create'),
            'edit' => EditGateCategory::route('/{record}/edit'),
        ];
    }
}
