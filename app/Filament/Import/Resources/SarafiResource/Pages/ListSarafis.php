<?php

namespace App\Filament\Import\Resources\SarafiResource\Pages;

use App\Filament\Import\Resources\SarafiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSarafis extends ListRecords
{
    protected static string $resource = SarafiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
