<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Safe;
use App\Models\Import\SafeTransaction;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class addsafe extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'افزودن به صندوق';
    protected static ?string $navigationGroup = 'حسابداری';
    protected static ?string $title = '';
    protected static ?int $navigationSort = 9;
    protected static string $view = 'filament.import.pages.addsafe';

    public $addAmount;
    public $note;

    public function addToSafe()
    {
        $userId = Auth::id();

        DB::transaction(function () use ($userId) {
            SafeTransaction::create([
                'amount' => (int) $this->addAmount,
                'note'   => $this->note,
            ]);

            $safe = Safe::where('user_id', $userId)->first();
            if ($safe) {
                $safe->increment('total', (int) $this->addAmount);
            }
        });

        $this->addAmount = null;
        $this->note = null;

        Notification::make()
            ->title('مبلغ به صندوق اضافه شد')
            ->success()
            ->send();
    }
}
