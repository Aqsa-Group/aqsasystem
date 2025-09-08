<?php

namespace App\Filament\Market\Resources\WithdrawLogResource\Pages;

use App\Filament\Market\Resources\WithdrawLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWithdrawLog extends EditRecord
{
    protected static string $resource = WithdrawLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
