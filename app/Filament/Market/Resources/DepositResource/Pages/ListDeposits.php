<?php

namespace App\Filament\Market\Resources\DepositResource\Pages;

use App\Filament\Market\Resources\DepositResource;
use Filament\Resources\Pages\ListRecords;

class ListDeposits extends ListRecords
{
    protected static string $resource = DepositResource::class;

    protected function canCreate(): bool
    {
        return false; 
    }
}
