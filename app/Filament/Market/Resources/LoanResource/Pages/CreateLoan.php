<?php

namespace App\Filament\Market\Resources\LoanResource\Pages;

use App\Filament\Market\Resources\LoanResource;
use App\Models\Market\LoanLog;
use App\Models\Market\Customer;  
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateLoan extends CreateRecord
{
    protected static string $resource = LoanResource::class;

    protected function beforeValidate(): void
    {
        $user = Auth::user();

        if (!in_array($user->role, ['superadmin', 'admin', 'Financial Manager'])) {
            throw ValidationException::withMessages([
                'person' => 'شما مجاز به برداشت از صندوق نیستید.'
            ]);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        $currencyNames = [
            'AFN' => 'افغانی',
            'USD' => 'دالر',
            'EUR' => 'یورو',
            'IRR' => 'تومان',
        ];

        $currencyName = $currencyNames[$data['currency']] ?? $data['currency'];

        if ($user->role === 'superadmin' || $user->role === 'admin') {
            $data['admin_id'] = $user->id;
        } else {
            $data['admin_id'] = $user->admin_id;
        }

        $totalIncoming = DB::connection('market')->table('accountings')
            ->where('expanses_type', $data['type'])
            ->where('currency', $data['currency'])
            ->where('paid', '>', 0)
            ->sum('paid');

        $totalOutgoing = DB::connection('market')->table('accountings')
            ->where('expanses_type', $data['type'])
            ->where('currency', $data['currency'])
            ->where('paid', '<', 0)
            ->sum('paid');

        $totalAvailable = $totalIncoming + $totalOutgoing;

        if ($totalAvailable <= 0) {
            Notification::make()
                ->title('خطا')
                ->body("موجودی {$data['type']} به ارز {$currencyName} صفر یا منفی است.")
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'amount' => "موجودی {$data['type']} به ارز {$currencyName} صفراست."
            ]);
        }

        if ($data['amount'] > $totalAvailable) {
            Notification::make()
                ->title('خطا')
                ->body("موجودی {$data['type']} به ارز {$currencyName} کافی نیست. شما فقط $totalAvailable موجودی دارید.")
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'amount' => "موجودی {$data['type']} به ارز {$currencyName} کافی نیست. شما فقط $totalAvailable موجودی دارید."
            ]);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $user = Auth::user();
        $record = $this->record;

        $currencyNames = [
            'AFN' => 'افغانی',
            'USD' => 'دالر',
            'EUR' => 'یورو',
            'IRR' => 'تومان',
        ];
        $currencyName = $currencyNames[$record->currency] ?? $record->currency;

        $totalIncoming = DB::connection('market')->table('accountings')
            ->where('expanses_type', $record->type)
            ->where('currency', $record->currency)
            ->where('paid', '>', 0)
            ->sum('paid');

        $totalOutgoing = DB::connection('market')->table('accountings')
            ->where('expanses_type', $record->type)
            ->where('currency', $record->currency)
            ->where('paid', '<', 0)
            ->sum('paid');

        $totalAvailable = $totalIncoming + $totalOutgoing;

        if ($totalAvailable <= 0 || $record->amount > $totalAvailable) {
            $record->delete();

            Notification::make()
                ->title('خطا')
                ->body("موجودی کافی نیست. عملیات ثبت قرضه لغو شد.")
                ->danger()
                ->send();

            return;
        }

        DB::connection('market')->table('accountings')->insert([
            'expanses_type' => $record->type,
            'currency' => $record->currency,
            'paid' => -1 * $record->amount,
            'type' => 'loan',
            'admin_id' => $user->role === 'superadmin' || $user->role === 'admin' ? $user->id : $user->admin_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $relatedId = match ($record->person) {
            'مشتری' => $record->customer_id,
            'دوکاندار' => $record->shopkeeper_id,
            'کارمند' => $record->staff_id,
            default => null,
        };

        $relatedType = match ($record->person) {
            'مشتری' => 'customer',
            'دوکاندار' => 'shopkeeper',
            'کارمند' => 'staff',
            default => null,
        };

        // **افزایش موجودی مشتری به اندازه مبلغ قرضه**
        if ($relatedType === 'customer' && $relatedId !== null) {
            $customer = Customer::find($relatedId);

            if ($customer) {
                $currency = strtoupper($record->currency);
                $balanceField = match ($currency) {
                    'AFN' => 'balance_afn',
                    'USD' => 'balance_usd',
                    'EUR' => 'balance_eur',
                    'IRR' => 'balance_irr',
                    default => null,
                };

                if ($balanceField && $customer->$balanceField !== null) {
                    $customer->$balanceField += $record->amount;
                    $customer->save();
                }
            }
        }

        LoanLog::create([
            'loan_id' => $record->id,
            'person' => $record->person,
            'related_id' => $relatedId,
            'related_type' => $relatedType,
            'currency' => $record->currency,
            'amount' => $record->amount,
            'expanses_type' => $record->type,
            'description' => $record->description,
            'date' => $record->date,
            'admin_id' => $user->role === 'superadmin' || $user->role === 'admin' ? $user->id : $user->admin_id,
        ]);

        Notification::make()
            ->title('قرضه ثبت شد و مبلغ از صندوق برداشت گردید')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
