<?php

namespace App\Filament\Import\Resources\CustomerLoanResource\Pages;

use App\Filament\Import\Resources\CustomerLoanResource;
use App\Models\Import\CustomerBalance;
use App\Models\Import\CustomerLoan;
use App\Models\Import\CustomerStory;
use App\Models\Import\Safe;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateCustomerLoan extends CreateRecord
{
    protected static string $resource = CustomerLoanResource::class;

    protected function handleRecordCreation(array $data): CustomerLoan
    {
        return DB::connection('import')->transaction(function () use ($data) {

            $user = Auth::guard('import')->user();

            $loan = CustomerLoan::create([
                ...$data,
                'user_id'  => $user->id,
                'admin_id' => $user->admin_id ?? $user->id,
            ]);

            // =========================
            // Customer Balance
            // =========================

            $balance = CustomerBalance::firstOrCreate(
                [
                    'customer_id' => $loan->customer_id,
                ],
                [
                    'afn' => 0,
                    'usd' => 0,
                    'eur' => 0,
                    'pkr' => 0,
                    'user_id' => $user->id,
                    'admin_id' => $user->admin_id ?? $user->id,
                ]
            );

            $currencyColumn = strtolower($loan->currency);

            if ($loan->type === 'رسید') {
                $balance->$currencyColumn += $loan->amount;
            } else {
                $balance->$currencyColumn -= $loan->amount;
            }

            $balance->save();

            // =========================
            // Customer Story
            // =========================

            CustomerStory::create([
                'customer_id'     => $loan->customer_id,
                'type'            => $loan->type,
                'amount'          => $loan->amount,
                'currency'        => $loan->currency,
                'date'            => $loan->date,
                'description'     => $loan->description,
                'user_id'         => $user->id,
                'admin_id'        => $user->admin_id ?? $user->id,
                'CustomerLoan_id' => $loan->id,
            ]);

            // =========================
            // Safe
            // =========================

            $safe = Safe::first();

            if (!$safe) {
                $safe = Safe::create([
                    'AFN' => 0,
                    'USD' => 0,
                    'user_id' => $user->id,
                    'today' => now()->toDateString(),
                ]);
            }

            $safeColumn = strtoupper($loan->currency);

            if ($loan->type === 'رسید') {

                $safe->$safeColumn += $loan->amount;

            } else {

                $safe->$safeColumn -= $loan->amount;

            }

            $safe->save();

            return $loan;
        });
    }
     protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}