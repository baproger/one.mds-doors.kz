<?php

namespace App\Filament\Resources\GateCategories\Pages;

use App\Filament\Resources\GateCategories\GateCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGateCategory extends EditRecord
{
    protected static string $resource = GateCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
