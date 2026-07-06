<?php

namespace App\Filament\Market\Resources\ExchangeResource\Pages;

use App\Filament\Market\Resources\ExchangeResource;
use App\Models\Market\Accounting;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditExchange extends EditRecord
{
    protected static string $resource = ExchangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $user = Auth::user();

        $adminId = in_array($user->role, ['superadmin', 'admin'])
            ? $user->id
            : $user->admin_id;

        Accounting::where('exchange_id', $record->id)
            ->where('type', 'تبادله صندوق')
            ->delete();

        Accounting::create([
            'expanses_type' => $record->from_type,
            'currency'      => $record->currency,
            'paid'          => -1 * $record->amount,
            'type'          => 'تبادله صندوق',
            'exchange_id'   => $record->id,
            'admin_id'      => $adminId,
        ]);

        Accounting::create([
            'expanses_type' => $record->to_type,
            'currency'      => $record->currency,
            'paid'          => $record->amount,
            'type'          => 'تبادله صندوق',
            'exchange_id'   => $record->id,
            'admin_id'      => $adminId,
        ]);
    }

   protected function beforeDelete(): void
{
    Accounting::where('exchange_id', $this->record->id)->delete();
}

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
