<?php

namespace App\Filament\Market\Resources\SafeResource\Pages;

use App\Filament\Market\Resources\SafeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSafe extends ViewRecord
{
    protected static string $resource = SafeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
