<?php

namespace App\Filament\Market\Resources\LoanResource\Pages;

use App\Filament\Market\Resources\LoanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoan extends EditRecord
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
