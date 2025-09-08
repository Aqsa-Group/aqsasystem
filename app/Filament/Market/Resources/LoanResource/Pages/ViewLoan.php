<?php

namespace App\Filament\Market\Resources\LoanResource\Pages;

use App\Filament\Market\Resources\LoanResource;
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
