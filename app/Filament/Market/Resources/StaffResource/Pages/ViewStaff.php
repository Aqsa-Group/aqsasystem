<?php

namespace App\Filament\Market\Resources\StaffResource\Pages;

use App\Filament\Market\Resources\StaffResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStaff extends ViewRecord
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
