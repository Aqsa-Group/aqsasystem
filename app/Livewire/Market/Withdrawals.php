<?php

namespace App\Livewire\Market;

use App\Models\Market\Customer;
use App\Models\Market\Staff;
use App\Models\Market\WithdrawLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class Withdrawals extends Component
{
    use WithPagination;

    // Properties
    public $type;
    public $currency = 'AFN';
    public $amount;
    public $receiver_type = 'staff';
    public $staff_id;
    public $customer_id;
    public $description;
    public $date;
    public $startDate;
    public $endDate;

    // State management
    public $editingId = null;
    public $confirmDeleteId = null;

    // Statistics
    public $withdrawalStats = [
        'today' => ['AFN' => 0, 'USD' => 0, 'EUR' => 0, 'IRR' => 0],
        'week' => ['AFN' => 0, 'USD' => 0, 'EUR' => 0, 'IRR' => 0],
        'month' => ['AFN' => 0, 'USD' => 0, 'EUR' => 0, 'IRR' => 0],
        'total' => ['AFN' => 0, 'USD' => 0, 'EUR' => 0, 'IRR' => 0]
    ];

    // Validation rules
    protected $rules = [
        'type' => 'required|string|max:255',
        'currency' => 'required|in:AFN,USD,EUR,IRR',
        'amount' => 'required|numeric|min:1',
        'receiver_type' => 'required|in:staff,customer',
        'description' => 'nullable|string|max:4000',
    ];

    protected $messages = [
        'type.required' => 'نوع برداشت الزامی است.',
        'currency.required' => 'انتخاب ارز الزامی است.',
        'amount.required' => 'مقدار برداشت الزامی است.',
        'amount.numeric' => 'مقدار باید عددی باشد.',
        'amount.min' => 'مقدار باید بزرگتر از صفر باشد.',
        'staff_id.required_if' => 'انتخاب کارمند الزامی است.',
        'customer_id.required_if' => 'انتخاب مشتری الزامی است.',
    ];

    /**
     * Initialize component
     */
    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->updateStats();
    }

    /**
     * Get authenticated user - مطابق با Filament
     */
    private function getAuthUser()
    {
        return Auth::guard('market')->user();
    }

    /**
     * Get admin ID - مطابق با Filament
     */
    private function getAdminId()
    {
        $user = $this->getAuthUser();
        return $user->role === 'admin' ? $user->id : $user->admin_id;
    }

    /**
     * Handle withdrawal submission
     */
    public function withdraw()
    {
        $this->validate();

        if ($this->editingId) {
            $this->updateWithdrawal();
            return;
        }

        $this->createWithdrawal();
    }

    private function createWithdrawal()
    {
        $user = $this->getAuthUser();
        $adminId = $this->getAdminId();

        // Check safe balance
        $total = DB::connection('market')->table('accountings')
    ->where('admin_id', $adminId)
    ->where('currency', $this->currency)
    ->where('expanses_type', $this->type)
    ->sum('paid');

        if ($this->amount > $total) {
            session()->flash('error', "موجودی کافی برای برداشت {$this->amount} {$this->currency} در صندوق وجود ندارد.");
            return;
        }

        // Process customer withdrawal if applicable
        if ($this->receiver_type === 'customer') {
            $customer = Customer::find($this->customer_id);

            if (!$customer) {
                session()->flash('error', 'مشتری یافت نشد.');
                return;
            }

            if ($this->type === 'کرایه دوکان‌های گروی و سرقفلی') {
                if ($customer->rent_money < $this->amount) {
                    session()->flash('error', "مشتری موجودی کافی برای برداشت {$this->amount} کرایه ندارد.");
                    return;
                }
                $customer->rent_money -= $this->amount;
                $customer->save();
            } else {
                $currencyField = match ($this->currency) {
                    'AFN' => 'balance_afn',
                    'USD' => 'balance_usd',
                    'EUR' => 'balance_eur',
                    'IRR' => 'balance_irr',
                };

                if ($customer->$currencyField < $this->amount) {
                    session()->flash('error', "مشتری موجودی کافی برای برداشت {$this->amount} {$this->currency} ندارد.");
                    return;
                }

                $customer->$currencyField -= $this->amount;
                $customer->save();
            }
        }

        // تبدیل تاریخ شمسی به میلادی
        $gregorianDate = Jalalian::fromFormat('Y/m/d', $this->date)->toCarbon()->setTimeFromTimeString(now()->format('H:i:s'));;

        DB::transaction(function () use ($adminId, $gregorianDate) {
            // Record in accountings table
            DB::connection('market')->table('accountings')->insert([
                'admin_id' => $adminId,
                'expanses_type' => $this->type,
                'currency' => $this->currency,
                'paid' => -1 * $this->amount,
                'type' => 'withdraw',
                'created_at' => $gregorianDate,
                'updated_at' => $gregorianDate,
            ]);

            // Record in withdraw_logs table
            DB::connection('market')->table('withdraw_logs')->insert([
                'expanses_type' => $this->type,
                'currency' => $this->currency,
                'amount' => $this->amount,
                'staff_id' => $this->receiver_type === 'staff' ? $this->staff_id : null,
                'customer_id' => $this->receiver_type === 'customer' ? $this->customer_id : null,
                'description' => $this->description,
                'admin_id' => $adminId,
                'created_at' => $gregorianDate,
                'updated_at' => $gregorianDate,
            ]);
        });

        session()->flash('message', 'برداشت از صندوق با موفقیت ثبت شد.');
        $this->resetForm();
        $this->updateStats();
    }

    private function updateWithdrawal()
    {
        $withdrawal = WithdrawLog::findOrFail($this->editingId);
        $adminId = $this->getAdminId();

        // تبدیل تاریخ شمسی به میلادی
        $gregorianDate = Jalalian::fromFormat('Y/m/d', $this->date)->toCarbon()->setTimeFromTimeString(now()->format('H:i:s'));

        DB::transaction(function () use ($withdrawal, $adminId, $gregorianDate) {
            // Reverse previous withdrawal FIRST
            $this->reversePreviousWithdrawal($withdrawal);

            // Check safe balance for new amount
            $total = DB::connection('market')->table('accountings')
                ->where('admin_id', $adminId)
                ->where('currency', $this->currency)
                ->where('expanses_type', $this->type)
                ->sum('paid');
            if ($this->amount > $total) {
                throw new \Exception("موجودی کافی برای برداشت {$this->amount} {$this->currency} در صندوق وجود ندارد.");
            }

            // Process customer withdrawal if applicable
            if ($this->receiver_type === 'customer' && $this->customer_id) {
                $customer = Customer::find($this->customer_id);

                if (!$customer) {
                    throw new \Exception('مشتری یافت نشد.');
                }

                if ($this->type === 'کرایه دوکان‌های گروی و سرقفلی') {
                    if ($customer->rent_money < $this->amount) {
                        throw new \Exception("مشتری موجودی کافی برای برداشت {$this->amount} کرایه ندارد.");
                    }
                    $customer->rent_money -= $this->amount;
                    $customer->save();
                } else {
                    $currencyField = match ($this->currency) {
                        'AFN' => 'balance_afn',
                        'USD' => 'balance_usd',
                        'EUR' => 'balance_eur',
                        'IRR' => 'balance_irr',
                    };

                    if ($customer->$currencyField < $this->amount) {
                        throw new \Exception("مشتری موجودی کافی برای برداشت {$this->amount} {$this->currency} ندارد.");
                    }

                    $customer->$currencyField -= $this->amount;
                    $customer->save();
                }
            }

            // Update withdrawal record
            $withdrawal->update([
                'expanses_type' => $this->type,
                'currency' => $this->currency,
                'amount' => $this->amount,
                'staff_id' => $this->receiver_type === 'staff' ? $this->staff_id : null,
                'customer_id' => $this->receiver_type === 'customer' ? $this->customer_id : null,
                'description' => $this->description,
                'created_at' => $gregorianDate,
                'updated_at' => now(),
            ]);

            // Record new accounting transaction with the same date
            DB::connection('market')->table('accountings')->insert([
                'admin_id' => $adminId,
                'expanses_type' => $this->type,
                'currency' => $this->currency,
                'paid' => -1 * $this->amount,
                'type' => 'withdraw',
                'created_at' => $gregorianDate,
                'updated_at' => now(),
            ]);
        });

        session()->flash('message', 'برداشت با موفقیت بروزرسانی شد.');
        $this->cancelEdit();
        $this->updateStats();
    }

    /**
     * Edit withdrawal
     */
    public function edit($id)
    {
        $withdrawal = WithdrawLog::find($id);

        if (!$withdrawal) {
            session()->flash('error', 'برداشت مورد نظر یافت نشد.');
            return;
        }

        $this->editingId = $id;
        $this->type = $withdrawal->expanses_type;
        $this->currency = $withdrawal->currency;
        $this->amount = $withdrawal->amount;
        $this->description = $withdrawal->description;

        // Determine receiver type based on what exists
        if ($withdrawal->staff_id) {
            $this->receiver_type = 'staff';
            $this->staff_id = $withdrawal->staff_id;
            $this->customer_id = null;
        } elseif ($withdrawal->customer_id) {
            $this->receiver_type = 'customer';
            $this->customer_id = $withdrawal->customer_id;
            $this->staff_id = null;
        } else {
            $this->receiver_type = 'staff';
            $this->staff_id = null;
            $this->customer_id = null;
        }

        session()->flash('message', 'در حال ویرایش برداشت...');
    }

    /**
     * Confirm deletion
     */
    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    /**
     * Delete withdrawal
     */
    public function deleteWithdrawal()
    {
        $withdrawal = WithdrawLog::find($this->confirmDeleteId);

        if (!$withdrawal) {
            session()->flash('error', 'برداشت مورد نظر یافت نشد.');
            return;
        }

        DB::transaction(function () use ($withdrawal) {
            $this->reversePreviousWithdrawal($withdrawal);

            // Delete the withdrawal log
            $withdrawal->delete();
        });

        session()->flash('message', 'برداشت با موفقیت حذف شد.');
        $this->confirmDeleteId = null;
        $this->updateStats();
    }

    /**
     * Cancel editing
     */
    public function cancelEdit()
    {
        $this->resetForm();
        $this->editingId = null;
    }

    /**
     * Reset form fields
     */
    private function resetForm()
    {
        $this->reset([
            'type',
            'currency',
            'amount',
            'receiver_type',
            'staff_id',
            'customer_id',
            'description'
        ]);
        $this->currency = 'AFN';
        $this->receiver_type = 'staff';
    }

    /**
     * Reverse previous withdrawal - CORRECTED VERSION
     */
    private function reversePreviousWithdrawal($withdrawal)
    {
        if (!$withdrawal) {
            throw new \Exception('برداشت معتبر نیست.');
        }

        $adminId = $this->getAdminId();

        // Return balance to customer if applicable
        if ($withdrawal->customer_id) {
            $customer = Customer::find($withdrawal->customer_id);
            if ($customer) {
                if ($withdrawal->expanses_type === 'کرایه دوکان‌های گروی و سرقفلی') {
                    $customer->rent_money += $withdrawal->amount;
                } else {
                    $currencyField = match ($withdrawal->currency) {
                        'AFN' => 'balance_afn',
                        'USD' => 'balance_usd',
                        'EUR' => 'balance_eur',
                        'IRR' => 'balance_irr',
                        default => 'balance_afn',
                    };
                    $customer->$currencyField += $withdrawal->amount;
                }
                $customer->save();
            }
        }

        // Find and delete the original accounting transaction instead of creating a reverse one
        $originalTransaction = DB::connection('market')->table('accountings')
            ->where('admin_id', $adminId)
            ->where('expanses_type', $withdrawal->expanses_type)
            ->where('currency', $withdrawal->currency)
            ->where('paid', -1 * $withdrawal->amount)
            ->where('type', 'withdraw')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($originalTransaction) {
            DB::connection('market')->table('accountings')
                ->where('id', $originalTransaction->id)
                ->delete();
        }
    }

    /**
     * Update statistics
     */
    private function updateStats()
    {
        $adminId = $this->getAdminId();

        $periods = [
            'today' => [today(), today()],
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
        ];

        foreach ($periods as $period => $dates) {
            $withdrawals = DB::connection('market')->table('withdraw_logs')
                ->where('admin_id', $adminId)
                ->whereBetween('created_at', $dates)
                ->get();

            $this->calculatePeriodStats($withdrawals, $period);
        }

        // Total stats
        $totalWithdrawals = DB::connection('market')->table('withdraw_logs')
            ->where('admin_id', $adminId)
            ->get();
        $this->calculatePeriodStats($totalWithdrawals, 'total');
    }

    /**
     * Calculate statistics for a period
     */
    private function calculatePeriodStats($withdrawals, $period)
    {
        $currencies = ['AFN', 'USD', 'EUR', 'IRR'];

        foreach ($currencies as $currency) {
            $this->withdrawalStats[$period][$currency] = $withdrawals
                ->where('currency', $currency)
                ->sum('amount');
        }
    }

    public function getExpansesTypesProperty()
    {
        $adminId = $this->getAdminId();

        $expansesTypes = DB::connection('market')->table('accountings')
            ->where('admin_id', $adminId)
            ->whereNotNull('expanses_type')
            ->where('expanses_type', '!=', '')
            ->distinct()
            ->orderBy('expanses_type', 'asc')
            ->pluck('expanses_type', 'expanses_type')
            ->toArray();

        if (empty($expansesTypes)) {
            $expansesTypes = [
                'برق' => 'برق',
                'آب' => 'آب',
                'گاز' => 'گاز',
                'اجاره' => 'اجاره',
                'حقوق' => 'حقوق کارمند',
                'خرید' => 'خرید ملزومات',
                'تعمیرات' => 'تعمیرات',
                'حمل و نقل' => 'حمل و نقل',
                'بیمه' => 'بیمه',
                'مالیات' => 'مالیات',
                'بازاریابی' => 'بازاریابی',
                'متفرقه' => 'متفرقه',
                'کرایه دوکان‌های گروی و سرقفلی' => 'کرایه دوکان‌های گروی و سرقفلی'
            ];
        }

        return $expansesTypes;
    }

    public function getStaffsProperty()
    {
        $adminId = $this->getAdminId();

        return Staff::where('admin_id', $adminId)
            ->pluck('fullname', 'id')
            ->toArray();
    }

    public function getCustomersProperty()
    {
        $adminId = $this->getAdminId();

        return Customer::where('admin_id', $adminId)
            ->get()
            ->mapWithKeys(fn($customer) => [
                $customer->id => $customer->fullname . ' - ' . $customer->phone
            ])
            ->toArray();
    }

    public function updatedFromDate()
    {
        $this->resetPage();
    }

    public function updatedToDate()
    {
        $this->resetPage();
    }


    /**
     * Set start date from JavaScript
     */
    public function setStartDate($date)
    {
        $this->startDate = $date;
        $this->resetPage();
    }

    /**
     * Set end date from JavaScript
     */
    public function setEndDate($date)
    {
        $this->endDate = $date;
        $this->resetPage();
    }

    public function updatedStartDate()
    {
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
    }

    public function getWithdrawalsProperty()
    {
        $adminId = $this->getAdminId();

        return WithdrawLog::with(['staff', 'customer'])
            ->where('admin_id', $adminId)

            ->when($this->startDate, function ($q) {
                try {
                    // تبدیل تاریخ شمسی به میلادی برای مقایسه
                    $from = Jalalian::fromFormat('Y/m/d', str_replace('-', '/', $this->startDate))
                        ->toCarbon()
                        ->startOfDay();

                    $q->where('created_at', '>=', $from);
                } catch (\Throwable $e) {
                    Log::warning('Invalid startDate: ' . $this->startDate);
                }
            })

            ->when($this->endDate, function ($q) {
                try {
                    // تبدیل تاریخ شمسی به میلادی برای مقایسه
                    $to = Jalalian::fromFormat('Y/m/d', str_replace('-', '/', $this->endDate))
                        ->toCarbon()
                        ->endOfDay();

                    $q->where('created_at', '<=', $to);
                } catch (\Throwable $e) {
                    Log::warning('Invalid endDate: ' . $this->endDate);
                }
            })

            ->orderByDesc('created_at')
            ->paginate(10);
    }





    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.market.withdrawals', [
            'withdrawals' => $this->withdrawals
        ]);
    }
}
