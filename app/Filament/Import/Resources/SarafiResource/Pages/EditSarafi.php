<?php

namespace App\Filament\Import\Resources\SarafiResource\Pages;

use App\Filament\Import\Resources\SarafiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSarafi extends EditRecord
{
    protected static string $resource = SarafiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
