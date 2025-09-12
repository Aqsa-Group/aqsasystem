<?php

namespace App\Filament\Market\Resources\OutsideResource\Pages;

use App\Filament\Market\Resources\OutsideResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use App\Models\Market\Accounting;
use App\Models\Market\Customer;
class EditOutside extends EditRecord
{
    protected static string $resource = OutsideResource::class;

    private ?int $oldPaid = null;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // مقدار paid اصلی رو ذخیره می‌کنیم
        $this->oldPaid = $this->record->paid;
        return $data;
    }

    protected function afterSave(): void
    {
        $outside = $this->record;
        $user = Auth::user();

        $adminIdToSave = ($user->role === 'superadmin' || $user->role === 'admin')
            ? $user->id
            : $user->admin_id;

        if ($outside->type === 'بیرونی' && $outside->paid > 0) {
            $accounting = DB::connection('market')->table('accountings')
                ->where('outside_id', $outside->id)
                ->first();

            if ($accounting) {
                DB::connection('market')->table('accountings')
                    ->where('id', $accounting->id)
                    ->update([
                        'currency' => $outside->currency,
                        'paid' => $outside->paid,
                        'admin_id' => $adminIdToSave,
                        'market_id' => $outside->market_id,
                        'updated_at' => now(),
                    ]);
            }
        }

        // بروزرسانی موجودی مشتری
        $customer = Customer::find($outside->customer_id);
        if ($customer) {
            $originalPaid = $this->oldPaid ?? 0;
            $newPaid = $outside->paid;
            $diff = $newPaid - $originalPaid;

            switch ($outside->currency) {
                case 'AFN':
                    $customer->balance_afn += $diff;
                    break;
                case 'USD':
                    $customer->balance_usd += $diff;
                    break;
                case 'EUR':
                    $customer->balance_eur += $diff;
                    break;
                case 'IRR':
                    $customer->balance_irr += $diff;
                    break;
            }

            $customer->save();

            Notification::make()
                ->title('موجودی مشتری بروزرسانی شد')
                ->success()
                ->send();
        }
    }

    protected function afterDelete(): void
{
    $outside = $this->record;

    // اول صندوق (Accounting) رو پاک یا آپدیت کن
    $accounting = DB::connection('market')->table('accountings')
        ->where('outside_id', $outside->id)
        ->first();

    if ($accounting) {
        DB::connection('market')->table('accountings')
            ->where('id', $accounting->id)
            ->delete(); // می‌تونی بجای delete مقدار paid رو صفر کنی
    }

    // حالا موجودی مشتری رو کم کن
    $customer = Customer::find($outside->customer_id);
    if ($customer) {
        switch ($outside->currency) {
            case 'AFN':
                $customer->balance_afn -= $outside->paid;
                break;
            case 'USD':
                $customer->balance_usd -= $outside->paid;
                break;
            case 'EUR':
                $customer->balance_eur -= $outside->paid;
                break;
            case 'IRR':
                $customer->balance_irr -= $outside->paid;
                break;
        }

        $customer->save();

        Notification::make()
            ->title('موجودی مشتری و صندوق بروزرسانی شد (حذف رکورد)')
            ->danger()
            ->send();
    }
}

}
