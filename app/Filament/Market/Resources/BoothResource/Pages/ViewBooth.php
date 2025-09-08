<?php

namespace App\Filament\Market\Resources\BoothResource\Pages;

use App\Filament\Market\Resources\BoothResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBooth extends ViewRecord
{
    protected static string $resource = BoothResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
