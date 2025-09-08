<?php

namespace App\Filament\Import\Resources\LoanResource\Pages;

use App\Filament\Import\Resources\LoanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLoan extends ViewRecord
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
