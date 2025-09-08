<?php

namespace App\Filament\Market\Resources\SafeResource\Pages;

use App\Filament\Market\Resources\SafeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSafe extends EditRecord
{
    protected static string $resource = SafeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
