<?php

namespace App\Filament\Market\Resources\WithdrawLogResource\Pages;

use App\Filament\Market\Resources\WithdrawLogResource;
use Filament\Resources\Pages\ListRecords;

class ListWithdrawLogs extends ListRecords
{
    protected static string $resource = WithdrawLogResource::class;

    public function getTitle(): string
    {
        return 'لاگ برداشت از صندوق ';
    }
}
