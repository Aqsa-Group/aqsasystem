<?php

namespace App\Filament\Import\Resources\BuyResource\Pages;

use App\Filament\Import\Resources\BuyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBuy extends EditRecord
{
    protected static string $resource = BuyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
