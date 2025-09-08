<?php

namespace App\Filament\Market\Resources\MarketResource\Pages;

use App\Filament\Market\Resources\MarketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarket extends EditRecord
{
    protected static string $resource = MarketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
