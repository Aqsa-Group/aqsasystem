<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Safe;
use App\Models\Import\Staff;
use App\Models\Import\Withdraw as WithdrawModel;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class Withdraw extends Page
{
    protected static string $view = 'filament.pages.withdraw';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'حسابداری';
    protected static ?string $navigationLabel = 'برداشت از صندوق';
    protected static ?int $navigationSort = 5;
    protected static ?string $title = null;

    // متغیرهای فرم
    public float $withdrawAmount = 0;
    public string $withdrawDescription = '';
    public string $withdrawType = '';
    public ?int $staffId = null;

    // برای نمایش لیست کارمندان
    public $staffList = [];

    public function mount()
    {
        $this->staffList = Staff::all();
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
    
        // موجودی صندوق کاربر جاری
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
    
        // کم کردن از صندوق
        $safe->total -= $this->withdrawAmount;
        $safe->save();
    
        // ذخیره برداشت
        WithdrawModel::create([
            'amount'      => $this->withdrawAmount,
            'description' => $this->withdrawDescription, 
            'type'        => $this->withdrawType,
            'user_id'     => Auth::id(),
            'staff_id'    => $this->withdrawType === 'salary' ? $this->staffId : null,
        ]);
    
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
