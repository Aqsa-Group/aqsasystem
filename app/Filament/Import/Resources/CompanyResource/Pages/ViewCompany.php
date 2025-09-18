<?php

namespace App\Filament\Import\Resources\CompanyResource\Pages;

use App\Filament\Import\Resources\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCompany extends ViewRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
