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
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'حسابداری';
    protected static ?string $navigationLabel = 'برداشت از صندوق';
    protected static ?int $navigationSort = 5;
    protected static ?string $title = null;

    public float $withdrawAmount = 0;
    public string $withdrawDescription = '';
    public string $withdrawType = '';
    public ?int $staffId = null;

    public $staffList = [];

    public function mount()
    {
        $this->staffList = Staff::all();
    }

    // وقتی کارمند تغییر کند
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
    if ($this->withdrawAmount <= 0 || empty($this->withdrawType)) {
        Notification::make()
            ->title('لطفاً مقدار و نوع برداشت معتبر وارد کنید!')
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

    // فقط برای حالت معاش: بررسی سقف ماهانه
    if ($this->withdrawType === 'salary') {
        // سقف ماهانه (به افغانی) — شما گفته بودید "ده هراز"
        $monthlyLimit = 10000;

        $now = Carbon::now();
        $month = $now->month;
        $year  = $now->year;

        // مجموع پرداخت‌های قبلی به این کارمند در ماه جاری
        $paidThisMonth = WithdrawModel::where('staff_id', $this->staffId)
            ->where('type', 'salary')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('amount');

        $remaining = $monthlyLimit - $paidThisMonth;

        if ($remaining <= 0) {
            Notification::make()
                ->title('سقف ماهانه پرداخت به این کارمند قبلاً تکمیل شده است.')
                ->body("تا این ماه دیگر اجازهٔ پرداخت وجود ندارد. (سقف: {$monthlyLimit})")
                ->danger()
                ->send();
            return;
        }

        if ($this->withdrawAmount > $remaining) {
            Notification::make()
                ->title('مقدار درخواستی بیشتر از سقف ماهانه است.')
                ->body("تا کنون {$paidThisMonth} افغانی پرداخت شده. باقی‌مانده برای این ماه: {$remaining} افغانی. لطفاً مقدار را کم کنید یا ابتدا باقی‌مانده را پرداخت کنید.")
                ->danger()
                ->send();

            // --- اگر می‌خواهید رفتار خودکار (برش زدن به باقی‌مانده) فعال باشد --- 
            // Uncomment کنید تا به‌صورت خودکار مقدار را به باقی‌مانده تغییر دهد و پرداخت انجام شود.
            //
            // $this->withdrawAmount = $remaining;
            // // ادامه پردازش را بدون return انجام بده (یعنی اجازه بده پرداخت با مقدار جدید انجام شود)
            //
            return;
        }
    }

    // ادامهٔ قبلی: موجودی صندوق کاربر جاری
    $safe = Safe::where('user_id', Auth::id())->first();

    if (!$safe) {
        Notification::make()
            ->title('موجودی صندوق شما صفر است یا یافت نشد!')
            ->danger()
            ->send();
        return;
    }

    if ($safe->total < $this->withdrawAmount) {
        Notification::make()
            ->title('موجودی صندوق شما کافی نیست!')
            ->danger()
            ->send();
        return;
    }

    // بهتر است عملیات DB در تراکنش انجام شود
    DB::transaction(function () use ($safe) {
        $safe->decrement('total', $this->withdrawAmount);

        WithdrawModel::create([
            'amount'      => $this->withdrawAmount,
            'description' => $this->withdrawDescription,
            'type'        => $this->withdrawType,
            'user_id'     => Auth::id(),
            'staff_id'    => $this->withdrawType === 'salary' ? $this->staffId : null,
        ]);
    });

    Notification::make()
        ->title("مبلغ {$this->withdrawAmount} افغانی از صندوق شما برداشت شد.")
        ->body($this->withdrawDescription ?: 'برداشت بدون توضیح')
        ->success()
        ->send();

    // ریست کردن فرم
    $this->withdrawAmount = 0;
    $this->withdrawDescription = '';
    $this->withdrawType = '';
    $this->staffId = null;
}

}
