<?php

namespace App\Filament\Market\Resources\SalaryResource\Pages;

use App\Filament\Market\Resources\SalaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSalary extends ViewRecord
{
    protected static string $resource = SalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
