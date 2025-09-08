<?php

namespace App\Filament\Import\Resources\WarehouseResource\Pages;

use App\Filament\Import\Resources\WarehouseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWarehouses extends ListRecords
{
    protected static string $resource = WarehouseResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label('ثبت جنس')

        ];
    }
}
