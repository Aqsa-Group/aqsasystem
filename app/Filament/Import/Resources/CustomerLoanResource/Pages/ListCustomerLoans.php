<?php

namespace App\Filament\Import\Resources\CustomerLoanResource\Pages;

use App\Filament\Import\Resources\CustomerLoanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerLoans extends ListRecords
{
    protected static string $resource = CustomerLoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
