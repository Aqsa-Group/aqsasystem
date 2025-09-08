<?php

namespace App\Filament\Market\Pages;

use App\Models\Market\Customer;
use App\Models\Market\Staff;
use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;


class WithdrawFromSafe extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'vaadin-money-withdraw';
    protected static string $view = 'filament.pages.withdraw-from-safe';
    protected static ?string $title = 'برداشت از صندوق';
    protected static ?string $navigationLabel = 'برداشت از صندوق';
    protected static ?string $navigationGroup = 'بخش مالی';

    public static function canAccess(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'Financial Manager' ,'admin']);
    }

    public $type;
    public $currency;
    public $amount;
    public $receiver_type = 'staff';
    public $staff_id;
    public $customer_id;
    public $description;

    public function mount()
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        $user = Auth::user();
        $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;

        $expansesTypes = DB::connection('market')->table('accountings')
            ->where('admin_id', $adminId)
            ->whereNotNull('expanses_type')
            ->distinct()
            ->pluck('expanses_type', 'expanses_type')
            ->toArray();

        return [
            Forms\Components\Grid::make(2)->schema([
                Select::make('type')
                    ->label('برداشت از')
                    ->options($expansesTypes)
                    ->required(),

                Select::make('currency')
                    ->label('ارز')
                    ->options([
                        'AFN' => 'افغانی',
                        'USD' => 'دالر',
                        'EUR' => 'یورو',
                        'IRR' => 'تومان',
                    ])
                    ->required(),

                TextInput::make('amount')
                    ->label('مقدار برداشت')
                    ->numeric()
                    ->required(),

                Select::make('receiver_type')
                    ->label('تحویل به')
                    ->options([
                        'staff' => 'کارمند',
                        'customer' => 'مشتری',
                    ])  
                    ->reactive(),

               
                    
                    Select::make('staff_id')
                        ->label('کارمند دریافت‌کننده')
                        ->options(function () {
                            $user = Auth::user();
                            $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;
                    
                            return Staff::where('admin_id', $adminId)
                                ->pluck('fullname', 'id');
                        })
                        ->visible(fn ($get) => $get('receiver_type') === 'staff')
                        ->searchable()
                        ->requiredIf('receiver_type', 'staff'),
                    
                    Select::make('customer_id')
                        ->label('مشتری دریافت‌کننده')
                        ->options(function ($get) {
                            $user = Auth::user();
                            $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;
                    
                            $marketId = $get('market_id');
                    
                            return Customer::query()
                                ->when($marketId, fn ($q) => $q->where('market_id', $marketId))
                                ->where('admin_id', $adminId)
                                ->get()
                                ->mapWithKeys(fn ($customer) => [
                                    $customer->id => $customer->fullname . ' - ' . $customer->phone
                                ]);
                        })
                        ->visible(fn ($get) => $get('receiver_type') === 'customer')
                        ->searchable()
                        ->requiredIf('receiver_type', 'customer'),
                    

                Textarea::make('description')
                    ->label('توضیحات')
                    ->rows(3)
                    ->placeholder('دلیل برداشت را وارد کنید...')
                    ->nullable()
                    ->columnSpanFull(), 
            ]),
        ];
    }

    public function withdraw(): void
    {
        $user = Auth::user();
        $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;
    
        $data = $this->form->getState();
    
        $total = DB::connection('market')->table('accountings')
            ->where('admin_id', $adminId)
            ->where('currency', $data['currency'])
            ->sum('paid');
    
        if ($data['amount'] > $total) {
            Notification::make()
                ->title('موجودی صندوق کافی نیست')
                ->body("موجودی کافی برای برداشت {$data['amount']} {$data['currency']} در صندوق وجود ندارد.")
                ->danger()
                ->send();
            return;
        }
    
        if ($data['receiver_type'] === 'customer') {
            $customer = Customer::find($data['customer_id']);
    
            if (!$customer) {
                Notification::make()
                    ->title('خطا در مشتری')
                    ->body('مشتری یافت نشد.')
                    ->danger()
                    ->send();
                return;
            }
    
            // اگر برداشت کرایه دوکان‌های گروی و سرقفلی باشد
            if ($data['type'] === 'کرایه دوکان‌های گروی و سرقفلی') {
                $rentField = 'rent_money';
    
                if ($customer->$rentField < $data['amount']) {
                    Notification::make()
                        ->title('موجودی کرایه کافی نیست')
                        ->body("مشتری موجودی کافی برای برداشت {$data['amount']} کرایه ندارد.")
                        ->danger()
                        ->send();
                    return;
                }
    
                // کم شدن از rent_money
                $customer->$rentField -= $data['amount'];
                $customer->save();
            } else {
                // سایر برداشت‌ها از موجودی اصلی مشتری
                $currencyField = match ($data['currency']) {
                    'AFN' => 'balance_afn',
                    'USD' => 'balance_usd',
                    'EUR' => 'balance_eur',
                    'IRR' => 'balance_irr',
                };
    
                if ($customer->$currencyField < $data['amount']) {
                    Notification::make()
                        ->title('موجودی مشتری کافی نیست')
                        ->body("مشتری موجودی کافی برای برداشت {$data['amount']} {$data['currency']} ندارد.")
                        ->danger()
                        ->send();
                    return;
                }
    
                $customer->$currencyField -= $data['amount'];
                $customer->save();
            }
        }
    
        // ثبت کاهش در صندوق (accountings)
        DB::connection('market')->table('accountings')->insert([
            'admin_id' => $adminId,
            'expanses_type' => $data['type'],
            'currency' => $data['currency'],
            'paid' => -1 * $data['amount'], // کم شدن از صندوق
            'type' => 'withdraw',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        // ثبت در withdraw_logs
        DB::connection('market')->table('withdraw_logs')->insert([
            'expanses_type' => $data['type'],
            'currency' => $data['currency'],
            'amount' => $data['amount'],
            'staff_id' => $data['receiver_type'] === 'staff' ? $data['staff_id'] : null,
            'customer_id' => $data['receiver_type'] === 'customer' ? $data['customer_id'] : null,
            'description' => $data['description'],
            'admin_id' => $adminId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        Notification::make()
            ->title('برداشت موفق')
            ->body('برداشت از صندوق و کاهش کرایه مشتری با موفقیت ثبت شد.')
            ->success()
            ->send();
    
        $this->redirectRoute('filament.market.resources.withdraw-logs.index');
    }
    
}


