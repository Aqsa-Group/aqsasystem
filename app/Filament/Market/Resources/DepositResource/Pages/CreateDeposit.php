<?php

namespace App\Filament\Market\Resources\DepositResource\Pages;

use App\Filament\Market\Resources\DepositResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Market\DepositLog;
use Illuminate\Support\Facades\Auth;

class CreateDeposit extends CreateRecord
{
    protected static string $resource = DepositResource::class;


protected function afterCreate(): void
{
    DepositLog::create([
        'deposit_id' => $this->record->id,
        'action' => 'create',
        'data' => $this->record->toArray(),
        'user_id' => Auth::id(),
    ]);
}

protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}

}
