<?php

namespace App\Filament\Market\Resources\OutsideResource\Pages;

use App\Filament\Market\Resources\OutsideResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOutside extends ViewRecord
{
    protected static string $resource = OutsideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
