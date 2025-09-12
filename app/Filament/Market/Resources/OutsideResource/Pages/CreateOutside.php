<?php

namespace App\Filament\Market\Resources\OutsideResource\Pages;

use App\Filament\Market\Resources\OutsideResource;
use App\Models\Market\Accounting;
use App\Models\Market\Customer;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateOutside extends CreateRecord
{
    protected static string $resource = OutsideResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['date'])) {
            $date = Carbon::parse($data['date'])->startOfDay();
            $data['date'] = $date->setTimeFromTimeString(now()->format('H:i:s'));
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $outside = $this->record;
        $user = Auth::user();
    
        if ($user->role === 'superadmin' || $user->role === 'admin') {
            $adminIdToSave = $user->id;
        } else {
            $adminIdToSave = $user->admin_id;
        }
    
        if ($outside->type === 'بیرونی' && $outside->paid > 0) {
            DB::connection('market')->table('accountings')->insert([
                 'outside_id' => $outside->id,
                'expanses_type' => 'عواید بیرونی',
                'currency' => $outside->currency,
                'paid' => 1 * $outside->paid,
                'admin_id' => $adminIdToSave,
                'type' => 'Outside',
                'market_id' => $outside->market_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    
        $customer = Customer::find($outside->customer_id);
        if ($customer) {
            switch ($outside->currency) {
                case 'AFN':
                    $customer->balance_afn += $outside->paid;
                    break;
                case 'USD':
                    $customer->balance_usd += $outside->paid;
                    break;
                case 'EUR':
                    $customer->balance_eur += $outside->paid;
                    break;
                case 'IRR':
                    $customer->balance_irr += $outside->paid;
                    break;
            }
    
            $customer->save();
    
            Notification::make()
                ->title('موجودی مشتری بروزرسانی شد')
                ->success()
                ->send();
        }
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}