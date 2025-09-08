<?php

namespace App\Filament\Market\Resources\LoanLogResource\Pages;

use App\Filament\Market\Resources\LoanLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoanLogs extends ListRecords
{
    protected static string $resource = LoanLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
