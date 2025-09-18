<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Safe;
use App\Models\Import\Staff;
use App\Models\Import\Withdraw as WithdrawModel;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class Withdraw extends Page
{
    protected static string $view = 'filament.pages.withdraw';
    protected static ?string $navigationIcon = 'iconoir-safe-arrow-right';
    protected static ?string $navigationGroup = 'حسابداری';
    protected static ?string $navigationLabel = 'برداشت از صندوق';
    protected static ?int $navigationSort = 5;
    protected static ?string $title = null;

    public float $withdrawAmount = 0;
    public string $withdrawDescription = '';
    public string $withdrawType = '';
    public ?int $staffId = null;
    public string $withdrawCurrency = '';


    public $staffList = [];

    public function mount()
    {
        $this->staffList = Staff::all();
    }

    public function updatedStaffId($value)
    {
        if ($this->withdrawType === 'salary' && $value) {
            $staff = Staff::find($value);
            if ($staff) {
                $this->withdrawAmount = $staff->salary;
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string|Htmlable
    {
        return '';
    }

public function withdrawFromSafe()
{
    if ($this->withdrawAmount <= 0 || empty($this->withdrawType) || empty($this->withdrawCurrency)) {
        Notification::make()
            ->title('لطفاً مقدار، نوع برداشت و ارز معتبر وارد کنید!')
            ->danger()
            ->send();
        return;
    }

    if ($this->withdrawType === 'salary' && !$this->staffId) {
        Notification::make()
            ->title('لطفاً کارمند را انتخاب کنید!')
            ->danger()
            ->send();
        return;
    }

    if ($this->withdrawType === 'salary') {
        $staff = Staff::find($this->staffId);
        if (!$staff) {
            Notification::make()
                ->title('کارمند انتخاب شده یافت نشد!')
                ->danger()
                ->send();
            return;
        }

        $monthlyLimit = $staff->salary;

        $now = Carbon::now();
        $month = $now->month;
        $year  = $now->year;

        $paidThisMonth = WithdrawModel::where('staff_id', $this->staffId)
            ->where('type', 'salary')
            ->where('currency', $this->withdrawCurrency)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('amount');

        $remaining = $monthlyLimit - $paidThisMonth;

        if ($remaining <= 0) {
            Notification::make()
                ->title('سقف ماهانه پرداخت به این کارمند قبلاً تکمیل شده است.')
                ->body("تا این ماه دیگر اجازهٔ پرداخت وجود ندارد. (سقف: {$monthlyLimit} {$this->withdrawCurrency})")
                ->danger()
                ->send();
            return;
        }

        if ($this->withdrawAmount > $remaining) {
            Notification::make()
                ->title('مقدار درخواستی بیشتر از سقف ماهانه است.')
                ->body("تا کنون {$paidThisMonth} {$this->withdrawCurrency} پرداخت شده. باقی‌مانده برای این ماه: {$remaining} {$this->withdrawCurrency}. لطفاً مقدار را کم کنید یا ابتدا باقی‌مانده را پرداخت کنید.")
                ->danger()
                ->send();
            return;
        }
    }

    $safe = Safe::where('user_id', Auth::id())->first();

    if (!$safe) {
        Notification::make()
            ->title('موجودی صندوق شما یافت نشد!')
            ->danger()
            ->send();
        return;
    }

    if ($this->withdrawCurrency === 'AFN') {
        if ($safe->AFN < $this->withdrawAmount) {
            Notification::make()
                ->title('موجودی افغانی صندوق کافی نیست!')
                ->danger()
                ->send();
            return;
        }
    } elseif ($this->withdrawCurrency === 'USD') {
        if ($safe->USD < $this->withdrawAmount) {
            Notification::make()
                ->title('موجودی دالر صندوق کافی نیست!')
                ->danger()
                ->send();
            return;
        }
    } else {
        Notification::make()
            ->title('نوع ارز انتخاب شده نامعتبر است!')
            ->danger()
            ->send();
        return;
    }

    DB::transaction(function () use ($safe) {
        if ($this->withdrawCurrency === 'AFN') {
            $safe->decrement('AFN', $this->withdrawAmount);
        } elseif ($this->withdrawCurrency === 'USD') {
            $safe->decrement('USD', $this->withdrawAmount);
        }

        WithdrawModel::create([
            'amount'      => $this->withdrawAmount,
            'description' => $this->withdrawDescription,
            'type'        => $this->withdrawType,
            'currency'    => $this->withdrawCurrency,
            'user_id'     => Auth::id(),
            'staff_id'    => $this->withdrawType === 'salary' ? $this->staffId : null,
        ]);
    });

    Notification::make()
        ->title("مبلغ {$this->withdrawAmount} {$this->withdrawCurrency} از صندوق شما برداشت شد.")
        ->body($this->withdrawDescription ?: 'برداشت بدون توضیح')
        ->success()
        ->send();

    $this->withdrawAmount = 0;
    $this->withdrawDescription = '';
    $this->withdrawType = '';
    $this->staffId = null;
    $this->withdrawCurrency = '';
}


}
