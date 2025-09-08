<?php

namespace App\Filament\Market\Resources\LoanLogResource\Pages;

use App\Filament\Market\Resources\LoanLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoanLog extends EditRecord
{
    protected static string $resource = LoanLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
