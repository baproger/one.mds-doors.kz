<?php

namespace App\Filament\Resources\GateCategories\Pages;

use App\Filament\Resources\GateCategories\GateCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGateCategories extends ListRecords
{
    protected static string $resource = GateCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Добавить категорию'),
        ];
    }
}
