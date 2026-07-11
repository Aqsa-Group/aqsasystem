<?php

namespace App\Livewire\Market;

use App\Models\Market\Customer;
use App\Models\Market\Staff;
use App\Models\Market\WithdrawDraft;
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

    // ==================== Properties برداشت نهایی ====================
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

    public $editingId = null;
    public $confirmDeleteId = null;

    // ==================== Properties پیش‌نویس (Draft) ====================
    public $showModal = false;
    public $draftType;
    public $draftCurrency = 'AFN';
    public $draftAmount;
    public $draftReceiverType = 'staff';
    public $draftStaffId;
    public $draftCustomerId;
    public $draftDescription;
    public $draftDate;
    public $draftTotalAmount = 0;
    public $editingDraftId = null;
    public $confirmDeleteIsDraft = false;
    

    // ==================== آمار برداشت‌های نهایی ====================
    public $withdrawalStats = [
        'today' => ['AFN' => 0, 'USD' => 0, 'EUR' => 0, 'IRR' => 0],
        'week' => ['AFN' => 0, 'USD' => 0, 'EUR' => 0, 'IRR' => 0],
        'month' => ['AFN' => 0, 'USD' => 0, 'EUR' => 0, 'IRR' => 0],
        'total' => ['AFN' => 0, 'USD' => 0, 'EUR' => 0, 'IRR' => 0]
    ];

    // ==================== قوانین اعتبارسنجی برداشت نهایی ====================
    protected $rules = [
        'type' => 'required|string|max:255',
        'currency' => 'required|in:AFN,USD,EUR,IRR',
        'amount' => 'required|numeric|min:1',
        'receiver_type' => 'required|in:staff,customer',
        'description' => 'nullable|string|max:4000',
        'staff_id' => 'nullable|exists:market.staff,id',
        'customer_id' => 'nullable|exists:market.customers,id',
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

    // ==================== قوانین اعتبارسنجی پیش‌نویس ====================
    protected $draftRules = [
        'draftType' => 'required|string|max:255',
        'draftDescription' => 'required|string|min:10',
        'draftDate' => 'required|string',
        'draftTotalAmount' => 'required|numeric|min:1',
    ];

    protected $draftMessages = [
        'draftType.required' => 'نوع برداشت الزامی است.',
        'draftDescription.required' => 'توضیحات برداشت الزامی است.',
        'draftDescription.min' => 'توضیحات باید حداقل ۱۰ کاراکتر باشد.',
        'draftDate.required' => 'تاریخ برداشت الزامی است.',
        'draftTotalAmount.required' => 'مبلغ کل برداشت الزامی است.',
        'draftTotalAmount.min' => 'مبلغ کل باید بزرگتر از صفر باشد.',
    ];

    // ==================== متدهای اولیه ====================
    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->draftDate = Jalalian::now()->format('Y/m/d');
        $this->updateStats();
    }

    private function getAuthUser()
    {
        return Auth::guard('market')->user();
    }

    private function getAdminId()
    {
        $user = $this->getAuthUser();
        return $user->role === 'admin' ? $user->id : $user->admin_id;
    }

    // ==================== متدهای برداشت نهایی ====================

    public function withdraw()
    {
        $this->validate();

        if ($this->editingId) {
            $this->updateWithdrawal();
            return;
        }

        $this->createWithdrawal();
    }

    


    /**
     * چاپ برداشت از طریق دکمه در جدول
     */
  public function printWithdrawal($id)
{
    $this->dispatch('print-pdf', url: route('recipt.print', $id));
}

    private function createWithdrawal()
    {
        $user = $this->getAuthUser();
        $adminId = $this->getAdminId();

       

        // پردازش مشتری (در صورت انتخاب) - بدون چک موجودی (اجازه منفی شدن)
        if ($this->receiver_type === 'customer' && $this->customer_id) {
            $customer = Customer::find($this->customer_id);
            if (!$customer) {
                session()->flash('error', 'مشتری یافت نشد.');
                return;
            }

            if ($this->type === 'کرایه دوکان‌های گروی و سرقفلی') {
                $customer->rent_money -= $this->amount;
            } else {
                $currencyField = match ($this->currency) {
                    'AFN' => 'balance_afn',
                    'USD' => 'balance_usd',
                    'EUR' => 'balance_eur',
                    'IRR' => 'balance_irr',
                };
                $customer->$currencyField -= $this->amount;
            }
            $customer->save();
        }

        // تاریخ میلادی
        $gregorianDate = Jalalian::fromFormat('Y/m/d', $this->date)->toCarbon()->setTimeFromTimeString(now()->format('H:i:s'));

        $withdrawLog = null;

        DB::transaction(function () use ($adminId, $gregorianDate, &$withdrawLog) {
            // ثبت در accountings
            DB::connection('market')->table('accountings')->insert([
                'admin_id' => $adminId,
                'expanses_type' => $this->type,
                'currency' => $this->currency,
                'paid' => -1 * $this->amount,
                'type' => 'withdraw',
                'created_at' => $gregorianDate,
                'updated_at' => $gregorianDate,
            ]);

            // ثبت در withdraw_logs با استفاده از مدل
            $withdrawLog = WithdrawLog::create([
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


        if ($withdrawLog) {
            $this->printWithdrawal($withdrawLog->id);
        }
        $this->resetForm();
        $this->updateStats();
    }

    private function updateWithdrawal()
    {
        $withdrawal = WithdrawLog::findOrFail($this->editingId);
        $adminId = $this->getAdminId();

        $gregorianDate = Jalalian::fromFormat('Y/m/d', $this->date)->toCarbon()->setTimeFromTimeString(now()->format('H:i:s'));

        DB::transaction(function () use ($withdrawal, $adminId, $gregorianDate) {
            // برگرداندن وضعیت قبلی
            $this->reversePreviousWithdrawal($withdrawal);

           

            // پردازش مشتری (در صورت وجود) - بدون چک موجودی
            if ($this->receiver_type === 'customer' && $this->customer_id) {
                $customer = Customer::find($this->customer_id);
                if (!$customer) {
                    throw new \Exception('مشتری یافت نشد.');
                }

                if ($this->type === 'کرایه دوکان‌های گروی و سرقفلی') {
                    $customer->rent_money -= $this->amount;
                } else {
                    $currencyField = match ($this->currency) {
                        'AFN' => 'balance_afn',
                        'USD' => 'balance_usd',
                        'EUR' => 'balance_eur',
                        'IRR' => 'balance_irr',
                    };
                    $customer->$currencyField -= $this->amount;
                }
                $customer->save();
            }

            // بروزرسانی رکورد برداشت
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

            // ثبت تراکنش جدید حسابداری
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

        // چاپ PDF پس از ویرایش
        $this->printWithdrawal($withdrawal->id);
        $this->cancelEdit();
        $this->updateStats();
    }

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
        $this->date = Jalalian::fromCarbon($withdrawal->created_at)->format('Y/m/d');

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

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
        $this->confirmDeleteIsDraft = false;
    }

    public function deleteWithdrawal()
    {
        if ($this->confirmDeleteIsDraft) {
            $this->deleteDraft($this->confirmDeleteId);
            return;
        }

        $withdrawal = WithdrawLog::find($this->confirmDeleteId);
        if (!$withdrawal) {
            session()->flash('error', 'برداشت مورد نظر یافت نشد.');
            return;
        }

        DB::transaction(function () use ($withdrawal) {
            $this->reversePreviousWithdrawal($withdrawal);
            $withdrawal->delete();
        });

        session()->flash('message', 'برداشت با موفقیت حذف شد.');
        $this->confirmDeleteId = null;
        $this->updateStats();
    }

    public function cancelEdit()
    {
        $this->resetForm();
        $this->editingId = null;
    }

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
        $this->date = Jalalian::now()->format('Y/m/d');
    }

    private function reversePreviousWithdrawal($withdrawal)
    {
        if (!$withdrawal) {
            throw new \Exception('برداشت معتبر نیست.');
        }

        $adminId = $this->getAdminId();

        // برگرداندن موجودی به مشتری (در صورت وجود)
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

        // حذف تراکنش حسابداری مرتبط
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

    // ==================== آمار ====================

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

        $totalWithdrawals = DB::connection('market')->table('withdraw_logs')
            ->where('admin_id', $adminId)
            ->get();
        $this->calculatePeriodStats($totalWithdrawals, 'total');
    }

    private function calculatePeriodStats($withdrawals, $period)
    {
        $currencies = ['AFN', 'USD', 'EUR', 'IRR'];

        foreach ($currencies as $currency) {
            $this->withdrawalStats[$period][$currency] = $withdrawals
                ->where('currency', $currency)
                ->sum('amount');
        }
    }

    // ==================== پراپرتی‌های محاسباتی ====================

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

    public function getWithdrawalsProperty()
    {
        $adminId = $this->getAdminId();

        return WithdrawLog::with(['staff', 'customer'])
            ->where('admin_id', $adminId)
            ->when($this->startDate, function ($q) {
                try {
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

    public function getDraftsProperty()
    {
        return WithdrawDraft::with(['staff', 'customer'])
            ->where('admin_id', $this->getAdminId())
            ->orderByDesc('created_at')
            ->get();
    }

    // ==================== متدهای فیلتر تاریخ ====================

    public function updatedFromDate()
    {
        $this->resetPage();
    }

    public function updatedToDate()
    {
        $this->resetPage();
    }

    public function setStartDate($date)
    {
        $this->startDate = $date;
        $this->resetPage();
    }

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

    // ==================== متدهای پیش‌نویس (Draft) ====================

    private function calculateTotalAmountFromDescription($description)
    {
        if (empty($description)) {
            return 0;
        }

        preg_match_all('/(?:مبلغ\s*)?(\d+)\s*افغانی/', $description, $matches);

        $total = 0;
        if (isset($matches[1])) {
            foreach ($matches[1] as $number) {
                $total += (int)$number;
            }
        }

        return $total;
    }

    public function updatedDraftDescription($value)
    {
        $this->draftTotalAmount = $this->calculateTotalAmountFromDescription($value);
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->resetDraftForm();
        $this->draftTotalAmount = 0;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetDraftForm();
        $this->editingDraftId = null;
    }

    public function submitDraft()
    {
        // محاسبه مجدد مبلغ کل
        $this->draftTotalAmount = $this->calculateTotalAmountFromDescription($this->draftDescription);

        // اعتبارسنجی
        $this->validate($this->draftRules, $this->draftMessages);

        if ($this->editingDraftId) {
            $this->updateDraft();
            return;
        }

        $adminId = $this->getAdminId();
        $gregorianDate = Jalalian::fromFormat('Y/m/d', $this->draftDate)
            ->toCarbon()
            ->setTimeFromTimeString(now()->format('H:i:s'));

        // مقداردهی پیش‌فرض (بدون گیرنده)
        $this->draftCurrency = 'AFN';
        $this->draftReceiverType = 'staff';
        $this->draftStaffId = null;
        $this->draftCustomerId = null;

        WithdrawDraft::create([
            'expanses_type' => $this->draftType,
            'currency'      => $this->draftCurrency,
            'amount'        => $this->draftTotalAmount,
            'staff_id'      => $this->draftStaffId,
            'customer_id'   => $this->draftCustomerId,
            'description'   => $this->draftDescription,
            'admin_id'      => $adminId,
            'created_at'    => $gregorianDate,
            'updated_at'    => $gregorianDate,
        ]);

        session()->flash('message', 'پیش‌نویس برداشت با موفقیت ذخیره شد.');
        $this->resetDraftForm();
        $this->updateStats();
    }

    private function updateDraft()
    {
        $draft = WithdrawDraft::findOrFail($this->editingDraftId);
        $gregorianDate = Jalalian::fromFormat('Y/m/d', $this->draftDate)
            ->toCarbon()
            ->setTimeFromTimeString(now()->format('H:i:s'));

        $draft->update([
            'expanses_type' => $this->draftType,
            'amount'        => $this->draftTotalAmount,
            'description'   => $this->draftDescription,
            'created_at'    => $gregorianDate,
            'updated_at'    => now(),
        ]);

        session()->flash('message', 'پیش‌نویس با موفقیت بروزرسانی شد.');
        $this->resetDraftForm();
        $this->editingDraftId = null;
        $this->updateStats();
    }

    public function editDraft($id)
    {
        $draft = WithdrawDraft::find($id);
        if (!$draft) {
            session()->flash('error', 'پیش‌نویس یافت نشد.');
            return;
        }

        $this->editingDraftId = $id;
        $this->draftType = $draft->expanses_type;
        $this->draftDescription = $draft->description;
        $this->draftDate = Jalalian::fromCarbon($draft->created_at)->format('Y/m/d');
        $this->draftTotalAmount = $draft->amount;

        $this->showModal = true;
    }

    public function cancelEditDraft()
    {
        $this->resetDraftForm();
        $this->editingDraftId = null;
    }

    /**
     * نهایی کردن یک پیش‌نویس (تبدیل به برداشت نهایی)
     */
    public function finalizeDraft($id)
    {
        $draft = WithdrawDraft::find($id);
        if (!$draft) {
            session()->flash('error', 'پیش‌نویس یافت نشد.');
            return;
        }

        try {
            DB::transaction(function () use ($draft) {
                $this->processFinalWithdrawal($draft);
            });
            session()->flash('message', 'پیش‌نویس با موفقیت نهایی شد.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        $this->updateStats();
    }

    /**
     * نهایی کردن همه پیش‌نویس‌ها
     */
    public function finalizeAllDrafts()
    {
        $drafts = WithdrawDraft::where('admin_id', $this->getAdminId())->get();

        if ($drafts->isEmpty()) {
            session()->flash('error', 'هیچ پیش‌نویسی برای نهایی کردن وجود ندارد.');
            return;
        }

        try {
            DB::transaction(function () use ($drafts) {
                foreach ($drafts as $draft) {
                    $this->processFinalWithdrawal($draft);
                }
            });
            session()->flash('message', 'همه پیش‌نویس‌ها با موفقیت نهایی شدند.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        $this->updateStats();
    }

    /**
     * پردازش نهایی یک پیش‌نویس (ثبت در accountings و withdraw_logs)
     * با مدیریت صحیح حالت بدون گیرنده
     */
    private function processFinalWithdrawal($draft)
    {
        $adminId = $this->getAdminId();

        // تنظیم متغیرهای اصلی
        $this->type = $draft->expanses_type;
        $this->currency = $draft->currency;
        $this->amount = $draft->amount;
        $this->description = $draft->description;
        $this->date = Jalalian::fromCarbon($draft->created_at)->format('Y/m/d');

        // تشخیص گیرنده (بر اساس موجودیت)
        if ($draft->staff_id) {
            $this->receiver_type = 'staff';
            $this->staff_id = $draft->staff_id;
            $this->customer_id = null;
        } elseif ($draft->customer_id) {
            $this->receiver_type = 'customer';
            $this->customer_id = $draft->customer_id;
            $this->staff_id = null;
        } else {
            // بدون گیرنده
            $this->receiver_type = 'staff';
            $this->staff_id = null;
            $this->customer_id = null;
        }

       

        // پردازش مشتری (فقط در صورت انتخاب مشتری) - بدون چک موجودی
        if ($this->receiver_type === 'customer' && $this->customer_id) {
            $customer = Customer::find($this->customer_id);
            if (!$customer) {
                throw new \Exception('مشتری یافت نشد.');
            }

            if ($this->type === 'کرایه دوکان‌های گروی و سرقفلی') {
                $customer->rent_money -= $this->amount;
            } else {
                $currencyField = match ($this->currency) {
                    'AFN' => 'balance_afn',
                    'USD' => 'balance_usd',
                    'EUR' => 'balance_eur',
                    'IRR' => 'balance_irr',
                };
                $customer->$currencyField -= $this->amount;
            }
            $customer->save();
        }

        // ثبت در accountings
        $gregorianDate = Jalalian::fromFormat('Y/m/d', $this->date)
            ->toCarbon()
            ->setTimeFromTimeString(now()->format('H:i:s'));

        DB::connection('market')->table('accountings')->insert([
            'admin_id' => $adminId,
            'expanses_type' => $this->type,
            'currency' => $this->currency,
            'paid' => -1 * $this->amount,
            'type' => 'withdraw',
            'created_at' => $gregorianDate,
            'updated_at' => $gregorianDate,
        ]);

        // ثبت در withdraw_logs
        WithdrawLog::create([
            'expanses_type' => $this->type,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'staff_id' => $this->staff_id,
            'customer_id' => $this->customer_id,
            'description' => $this->description,
            'admin_id' => $adminId,
            'created_at' => $gregorianDate,
            'updated_at' => $gregorianDate,
        ]);

        // حذف پیش‌نویس
        $draft->delete();
    }

    public function deleteDraft($id)
    {
        $draft = WithdrawDraft::find($id);
        if ($draft) {
            $draft->delete();
            session()->flash('message', 'پیش‌نویس با موفقیت حذف شد.');
        }
        $this->confirmDeleteId = null;
        $this->confirmDeleteIsDraft = false;
        $this->updateStats();
    }

    public function confirmDeleteDraft($id)
    {
        $this->confirmDeleteId = $id;
        $this->confirmDeleteIsDraft = true;
    }

    private function resetDraftForm()
    {
        $this->reset([
            'draftType',
            'draftCurrency',
            'draftAmount',
            'draftReceiverType',
            'draftStaffId',
            'draftCustomerId',
            'draftDescription',
            'draftTotalAmount',
        ]);
        $this->draftCurrency = 'AFN';
        $this->draftReceiverType = 'staff';
        $this->draftDate = Jalalian::now()->format('Y/m/d');
        $this->draftTotalAmount = 0;
        $this->editingDraftId = null;
    }

    // ==================== رندر ====================

    public function render()
    {
        return view('livewire.market.withdrawals', [
            'withdrawals' => $this->withdrawals,
            'drafts'      => $this->drafts,
        ]);
    }
}
