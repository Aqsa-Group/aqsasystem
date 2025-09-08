<?php

namespace App\Filament\Market\Resources\AccountingResource\Pages;

use App\Filament\Market\Resources\AccountingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAccountings extends ListRecords
{
    protected static string $resource = AccountingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->whereIn('type', ['دوکان', 'غرفه']);
    }
}
