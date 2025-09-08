<?php

namespace App\Filament\Market\Resources\SalaryResource\Pages;

use App\Filament\Market\Resources\SalaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalary extends EditRecord
{
    protected static string $resource = SalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
