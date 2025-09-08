<?php

namespace App\Filament\Market\Resources\OutsideResource\Pages;

use App\Filament\Market\Resources\OutsideResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOutside extends EditRecord
{
    protected static string $resource = OutsideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
