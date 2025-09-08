<?php

namespace App\Filament\Market\Resources\SellResource\Pages;

use App\Filament\Market\Resources\SellResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSell extends EditRecord
{
    protected static string $resource = SellResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
