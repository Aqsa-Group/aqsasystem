<?php

namespace App\Filament\Import\Resources\SarafiResource\Pages;

use App\Filament\Import\Resources\SarafiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSarafi extends ViewRecord
{
    protected static string $resource = SarafiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
