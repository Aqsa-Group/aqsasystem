<?php

namespace App\Filament\Import\Resources\CustomerLoanResource\Pages;

use App\Filament\Import\Resources\CustomerLoanResource;
use App\Models\Import\CustomerBalance;
use App\Models\Import\CustomerStory;
use App\Models\Import\Safe;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditCustomerLoan extends EditRecord
{
    protected static string $resource = CustomerLoanResource::class;

    // ==========================
    // UPDATE (اصلاح کامل)
    // ==========================
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        DB::connection('import')->transaction(function () use ($record, $data) {

            // نگه‌داشتن مقدار قبلی
            $oldCurrency = strtolower($record->currency);
            $oldAmount = $record->amount;
            $oldType = $record->type;

            // ==========================
            // 1. Rollback اثر قبلی
            // ==========================
            $balance = CustomerBalance::where('customer_id', $record->customer_id)->first();

            if ($balance) {

                if ($oldType === 'رسید') {
                    $balance->$oldCurrency += $oldAmount;
                } else {
                    $balance->$oldCurrency -= $oldAmount;
                }

                $balance->save();
            }

            $safe = Safe::first();

            if ($safe) {
                $safeColumn = strtoupper($oldCurrency);

                if (isset($safe->$safeColumn)) {

                    if ($oldType === 'رسید') {
                        $safe->$safeColumn += $oldAmount;
                    } else {
                        $safe->$safeColumn -= $oldAmount;
                    }

                    $safe->save();
                }
            }

            // ==========================
            // 2. Update رکورد
            // ==========================
            $record->update($data);

            // ==========================
            // 3. Apply اثر جدید
            // ==========================
            $newCurrency = strtolower($record->currency);
            $newAmount = $record->amount;
            $newType = $record->type;

            $newBalance = CustomerBalance::firstOrCreate(
                ['customer_id' => $record->customer_id],
                [
                    'afn' => 0,
                    'usd' => 0,
                    'eur' => 0,
                    'pkr' => 0,
                    'user_id' => $record->user_id,
                    'admin_id' => $record->admin_id,
                ]
            );

            if ($newType === 'رسید') {
                $newBalance->$newCurrency += $newAmount;
            } else {
                $newBalance->$newCurrency -= $newAmount;
            }

            $newBalance->save();

            $safe = Safe::first();

            if ($safe) {
                $safeColumn = strtoupper($newCurrency);

                if (isset($safe->$safeColumn)) {

                    if ($newType === 'رسید') {
                        $safe->$safeColumn += $newAmount;
                    } else {
                        $safe->$safeColumn -= $newAmount;
                    }

                    $safe->save();
                }
            }

            // ==========================
            // 4. Update Story
            // ==========================
            CustomerStory::where('CustomerLoan_id', $record->id)
                ->update([
                    'customer_id' => $record->customer_id,
                    'type' => $record->type,
                    'amount' => $record->amount,
                    'currency' => $record->currency,
                    'date' => $record->date,
                    'description' => $record->description,
                ]);
        });

        return $record->refresh();
    }

 
}
