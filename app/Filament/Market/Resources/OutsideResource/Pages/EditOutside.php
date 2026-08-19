<?php

namespace App\Filament\Market\Resources\OutsideResource\Pages;

use App\Filament\Market\Resources\OutsideResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use App\Models\Market\Customer;

class EditOutside extends EditRecord
{
    protected static string $resource = OutsideResource::class;

    private ?float $oldPaid = null;
    private ?int $oldCustomerId = null;
    private ?string $oldCurrency = null;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // اطلاعات قبل از ویرایش
        $this->oldPaid = (float) ($this->record->paid ?? 0);
        $this->oldCustomerId = $this->record->customer_id;
        $this->oldCurrency = $this->record->currency;

        return $data;
    }

    protected function afterSave(): void
    {
        $outside = $this->record;
        $user = Auth::user();

        $adminIdToSave = ($user->role === 'superadmin' || $user->role === 'admin')
            ? $user->id
            : $user->admin_id;

        /*
        |--------------------------------------------------------------------------
        | بروزرسانی Accounting
        |--------------------------------------------------------------------------
        */

        if ($outside->type === 'بیرونی' && $outside->paid > 0) {

            $accounting = DB::connection('market')
                ->table('accountings')
                ->where('outside_id', $outside->id)
                ->first();

            if ($accounting) {

                DB::connection('market')
                    ->table('accountings')
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

        /*
        |--------------------------------------------------------------------------
        | بروزرسانی بیلانس مشتری
        |--------------------------------------------------------------------------
        */

        $oldCustomerId = $this->oldCustomerId;
        $newCustomerId = $outside->customer_id;

        $oldPaid = (float) ($this->oldPaid ?? 0);
        $newPaid = (float) ($outside->paid ?? 0);

        $oldCurrency = $this->oldCurrency;
        $newCurrency = $outside->currency;

        /*
        |--------------------------------------------------------------------------
        | حالت 1:
        | مشتری قبلی وجود داشته
        | مشتری جدید همان مشتری است
        |--------------------------------------------------------------------------
        */

        if ($oldCustomerId && $oldCustomerId == $newCustomerId) {

            $customer = Customer::find($newCustomerId);

            if ($customer) {

                // اگر ارز تغییر نکرده
                if ($oldCurrency === $newCurrency) {

                    $diff = $newPaid - $oldPaid;

                    $this->changeCustomerBalance(
                        $customer,
                        $newCurrency,
                        $diff
                    );

                } else {

                    // ارز قبلی را برگردان
                    $this->changeCustomerBalance(
                        $customer,
                        $oldCurrency,
                        -$oldPaid
                    );

                    // ارز جدید را اضافه کن
                    $this->changeCustomerBalance(
                        $customer,
                        $newCurrency,
                        $newPaid
                    );
                }

                $customer->save();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | حالت 2:
        | قبلاً مشتری نداشته
        | الان مشتری انتخاب شده
        |--------------------------------------------------------------------------
        */

        elseif (!$oldCustomerId && $newCustomerId) {

            $customer = Customer::find($newCustomerId);

            if ($customer) {

                // کل مبلغ جدید به بیلانس مشتری اضافه شود
                $this->changeCustomerBalance(
                    $customer,
                    $newCurrency,
                    $newPaid
                );

                $customer->save();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | حالت 3:
        | قبلاً مشتری داشته
        | الان مشتری حذف شده
        |--------------------------------------------------------------------------
        */

        elseif ($oldCustomerId && !$newCustomerId) {

            $oldCustomer = Customer::find($oldCustomerId);

            if ($oldCustomer) {

                // مبلغ قبلی از مشتری قبلی کم شود
                $this->changeCustomerBalance(
                    $oldCustomer,
                    $oldCurrency,
                    -$oldPaid
                );

                $oldCustomer->save();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | حالت 4:
        | مشتری از یک شخص به شخص دیگر تغییر کرده
        |--------------------------------------------------------------------------
        */

        elseif (
            $oldCustomerId &&
            $newCustomerId &&
            $oldCustomerId != $newCustomerId
        ) {

            // از مشتری قبلی کم کن
            $oldCustomer = Customer::find($oldCustomerId);

            if ($oldCustomer) {

                $this->changeCustomerBalance(
                    $oldCustomer,
                    $oldCurrency,
                    -$oldPaid
                );

                $oldCustomer->save();
            }

            // به مشتری جدید اضافه کن
            $newCustomer = Customer::find($newCustomerId);

            if ($newCustomer) {

                $this->changeCustomerBalance(
                    $newCustomer,
                    $newCurrency,
                    $newPaid
                );

                $newCustomer->save();
            }
        }

        Notification::make()
            ->title('اطلاعات عواید و موجودی مشتری بروزرسانی شد')
            ->success()
            ->send();
    }

    /**
     * تغییر موجودی مشتری بر اساس ارز
     */
    private function changeCustomerBalance(
        Customer $customer,
        ?string $currency,
        float $amount
    ): void {

        switch ($currency) {

            case 'AFN':
                $customer->balance_afn =
                    (float) $customer->balance_afn + $amount;
                break;

            case 'USD':
                $customer->balance_usd =
                    (float) $customer->balance_usd + $amount;
                break;

            case 'EUR':
                $customer->balance_eur =
                    (float) $customer->balance_eur + $amount;
                break;

            case 'IRR':
                $customer->balance_irr =
                    (float) $customer->balance_irr + $amount;
                break;
        }
    }

    protected function afterDelete(): void
    {
        $outside = $this->record;

        /*
        |--------------------------------------------------------------------------
        | حذف Accounting
        |--------------------------------------------------------------------------
        */

        $accounting = DB::connection('market')
            ->table('accountings')
            ->where('outside_id', $outside->id)
            ->first();

        if ($accounting) {

            DB::connection('market')
                ->table('accountings')
                ->where('id', $accounting->id)
                ->delete();
        }

        /*
        |--------------------------------------------------------------------------
        | کم کردن مبلغ از بیلانس مشتری
        |--------------------------------------------------------------------------
        */

        if ($outside->customer_id) {

            $customer = Customer::find($outside->customer_id);

            if ($customer) {

                $this->changeCustomerBalance(
                    $customer,
                    $outside->currency,
                    -(float) $outside->paid
                );

                $customer->save();

                Notification::make()
                    ->title('موجودی مشتری و صندوق بروزرسانی شد')
                    ->danger()
                    ->send();
            }
        }
    }

    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}