<?php

namespace App\Filament\Market\Resources\OutsideResource\Pages;

use App\Filament\Market\Resources\OutsideResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOutsides extends ListRecords
{
    protected static string $resource = OutsideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
