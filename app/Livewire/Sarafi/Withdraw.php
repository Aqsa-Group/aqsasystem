<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Staffs;
use App\Models\Sarafi\Withdraws;
use App\Models\Sarafi\User;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Journals;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;
use NumberFormatter;
use Illuminate\Support\Facades\DB;

class Withdraw extends Component
{
    use WithPagination, WithFileUploads;

    // Search, modal and edit state
    public $search = '';
    public $transactionId = null;
    public $confirmDeleteId = null;

    // Withdraw Form fields
    public $selectedStaff = null;
    public $staff_id, $expanses_type, $amount, $currency = 'afn';
    public $date, $description, $file;
    public $searchStaff = '';

    // For formatted display
    public $formatted_amount = '';
    public $amount_in_words = '';

    // Temp URLs for preview
    public $tempFileUrl = null;

    // Alerts
    public $alert = null;
    public $staffs = [];

    // Cache management
    protected $cacheKeys = [
        'withdraws_list' => 'withdraws_list_',
        'staffs_list' => 'staffs_list_',
    ];

    public $cacheTimestamp = null;

    // Currencies
    public $currencies = [
        ['code' => 'afn', 'name_fa' => 'افغانی'],
        ['code' => 'usd', 'name_fa' => 'دالر'],
        ['code' => 'irr', 'name_fa' => 'تومان'],
        ['code' => 'eur', 'name_fa' => 'یورو'],
        ['code' => 'pkr', 'name_fa' => 'کلدار'],
        ['code' => 'aed', 'name_fa' => 'درهم'],
        ['code' => 'try', 'name_fa' => 'لیره'],
        ['code' => 'cny', 'name_fa' => 'یوان'],
        ['code' => 'inr', 'name_fa' => 'روپیه'],
    ];

    // Validation rules
    protected $rules = [
        'staff_id' => 'required|exists:sarafi.staffs,id',
        'expanses_type' => 'required|string|max:100',
        'amount' => 'required|integer|min:0',
        'currency' => 'required|string|max:3',
        'date' => 'required|date',
        'description' => 'nullable|string|max:500',
        'file' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
    ];

    public function mount()
    {
        $todayJalali = Jalalian::now();
        $this->date = $todayJalali->format('Y/m/d');
        $this->expanses_type = 'کرایه';
        $this->cacheTimestamp = time();

        $this->loadStaffs();
    }

    private function loadStaffs()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $this->staffs = Staffs::where('admin_id', $adminId)
            ->select('id', 'name', 'fathername', 'job', 'phone', 'image')
            ->get()
            ->map(function ($staff) {
                return [
                    'id' => $staff->id,
                    'name' => $staff->name,
                    'fathername' => $staff->fathername,
                    'job' => $staff->job,
                    'phone' => $staff->phone,
                    'image' => $staff->image,
                    'display_text' => $staff->name . ' (' . $staff->job . ')'
                ];
            })->toArray();
    }

    // Reset form fields
    public function resetInputFields()
    {
        $this->reset([
            'staff_id',
            'expanses_type',
            'amount',
            'currency',
            'date',
            'description',
            'file',
            'transactionId',
            'formatted_amount',
            'amount_in_words',
            'selectedStaff',
            'searchStaff'
        ]);

        $this->tempFileUrl = null;

        // Reset validation errors
        $this->resetErrorBag();
        $this->resetValidation();

        // Reset to default values
        $todayJalali = Jalalian::now();
        $this->date = $todayJalali->format('Y/m/d');
        $this->expanses_type = 'salary';
        $this->currency = 'afn';
    }

    // Select staff
    public function selectStaff($id)
    {
        $staff = Staffs::find($id);
        if ($staff) {
            $this->selectedStaff = $staff;
            $this->staff_id = $id;
            $this->searchStaff = $staff->name . ' (' . $staff->job . ')';
        }
    }

    public function clearStaff()
    {
        $this->selectedStaff = null;
        $this->staff_id = null;
        $this->searchStaff = '';
    }

    // Handle amount input - convert formatted string to integer
    public function updatedFormattedAmount($value)
    {
        // تبدیل اعداد فارسی و عربی به انگلیسی
        $value = $this->convertPersianArabicToEnglish($value);

        // حذف تمام کاراکترهای غیرعددی (کاما، نقطه، فاصله)
        $cleaned = preg_replace('/[^\d]/', '', $value);

        // تبدیل به عدد صحیح
        $this->amount = $cleaned ? (int)$cleaned : 0;

        // نمایش فرمت شده برای کاربر
        $this->formatted_amount = $this->amount ? number_format($this->amount) : '';

        // تولید متن به حروف
        $this->generateAmountInWords();
    }

    // تابع برای تبدیل اعداد فارسی و عربی به انگلیسی
    private function convertPersianArabicToEnglish($value)
    {
        if (!$value) return $value;

        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $value = str_replace($persian, $english, $value);
        $value = str_replace($arabic, $english, $value);

        return $value;
    }

    // تولید مبلغ به حروف
    private function generateAmountInWords()
    {
        if ($this->amount > 0) {
            try {
                $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);
                $words = $formatter->format($this->amount);

                // اصلاح برخی کلمات برای خوانایی بهتر
                $words = str_replace(
                    ['دویست', 'سیصد', 'پانصد', 'هزار', 'میلیون', 'بیلیون'],
                    ['دوصد', 'سه‌صد', 'پانصد', 'هزار', 'میلیون', 'میلیارد'],
                    $words
                );

                $this->amount_in_words = $words . ' ' . $this->getCurrencyName($this->currency);
            } catch (\Exception $e) {
                Log::error('Error generating amount in words: ' . $e->getMessage());
                $this->amount_in_words = 'خطا در تولید متن';
            }
        } else {
            $this->amount_in_words = '';
        }
    }

    // Get currency name
    private function getCurrencyName($code)
    {
        foreach ($this->currencies as $currency) {
            if ($currency['code'] === $code) {
                return $currency['name_fa'];
            }
        }
        return $code;
    }

    // Preview file before upload
    public function updatedFile()
    {
        $this->validateOnly('file');
        $this->tempFileUrl = $this->file->temporaryUrl();
    }

    // Remove file
    public function removeFile()
    {
        $this->file = null;
        $this->tempFileUrl = null;
        $this->resetErrorBag('file');
    }

    // Load withdraw for editing
    public function edit($id)
    {
        $withdraw = Withdraws::findOrFail($id);
        $staff = $withdraw->staff;

        $this->transactionId = $id;
        $this->staff_id = $withdraw->staff_id;
        $this->selectedStaff = $staff;
        $this->expanses_type = $withdraw->expanses_type;
        $this->amount = (int)$withdraw->amount;
        $this->formatted_amount = $withdraw->amount ? number_format($withdraw->amount) : '';
        $this->currency = $withdraw->currency;
        $this->date = $withdraw->date;
        $this->description = $withdraw->description;
        $this->searchStaff = $staff ? $staff->name . ' (' . $staff->job . ')' : '';

        // تولید متن به حروف
        $this->generateAmountInWords();

        // Scroll to form
        $this->dispatch('scroll-to-form');
    }

    // Save or update withdraw
    public function save()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // اطمینان از اینکه amount عدد صحیح است
        $this->amount = $this->convertToInteger($this->amount);

        // اعتبارسنجی
        $this->validate();

        // استفاده از تراکنش برای اطمینان از یکپارچگی داده‌ها
        DB::connection('sarafi')->beginTransaction();

        try {
            $data = [
                'staff_id' => $this->staff_id,
                'expanses_type' => $this->expanses_type,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'date' => $this->date,
                'description' => $this->description,
                'admin_id' => $adminId,
                'user_id' => $user->id,
            ];

            // Handle file upload
            if ($this->file) {
                $data['file'] = $this->file->store('withdraws/documents', 'public');
            }

            if ($this->transactionId) {
                // ویرایش برداشت
                $withdraw = Withdraws::findOrFail($this->transactionId);
                
                // برگشت مبلغ قدیم به صندوق
                $this->updateCurrencySafe($adminId, $withdraw->currency, $withdraw->amount);
                
                // کم کردن مبلغ جدید از صندوق
                $this->updateCurrencySafe($adminId, $this->currency, -$this->amount);
                
                // Delete old file if editing
                if ($withdraw->file && Storage::disk('public')->exists($withdraw->file)) {
                    Storage::disk('public')->delete($withdraw->file);
                }
                
                $withdraw->update($data);
                $message = 'برداشت با موفقیت بروزرسانی شد.';
            } else {
                // ثبت برداشت جدید
                // کم کردن مبلغ از صندوق
                $this->updateCurrencySafe($adminId, $this->currency, -$this->amount);
                
                Withdraws::create($data);
                $message = 'برداشت جدید با موفقیت ثبت شد.';
            }

            DB::connection('sarafi')->commit();

            // Invalidate cache
            $this->invalidateCache();

            // Update cache timestamp
            $this->cacheTimestamp = time();

            $this->alert = [
                'type' => 'success',
                'message' => $message
            ];

            $this->resetInputFields();

            // Force refresh
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            DB::connection('sarafi')->rollBack();

            $this->alert = [
                'type' => 'error',
                'message' => 'خطا در ثبت برداشت: ' . $e->getMessage()
            ];

            Log::error('Error saving withdraw: ' . $e->getMessage());
        }
    }

    // Helper method to convert any input to integer
    private function convertToInteger($value)
    {
        if (is_null($value)) {
            return 0;
        }

        if (is_numeric($value)) {
            return (int)$value;
        }

        // حذف تمام کاراکترهای غیرعددی
        $cleaned = preg_replace('/[^\d]/', '', $value);

        return $cleaned ? (int)$cleaned : 0;
    }

    // Confirm deletion
    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    // Clear alert
    public function clearAlert()
    {
        $this->alert = null;
    }

    // Delete withdraw
    public function delete()
    {
        if ($this->confirmDeleteId) {
            DB::connection('sarafi')->beginTransaction();

            try {
                $withdraw = Withdraws::findOrFail($this->confirmDeleteId);
                $user = Auth::guard('sarafi')->user();
                $adminId = $user->admin_id ?? $user->id;

                // برگشت مبلغ به صندوق
                $this->updateCurrencySafe($adminId, $withdraw->currency, $withdraw->amount);

                // Delete associated file
                if ($withdraw->file && Storage::disk('public')->exists($withdraw->file)) {
                    Storage::disk('public')->delete($withdraw->file);
                }

                $withdraw->delete();

                DB::connection('sarafi')->commit();

                // Invalidate cache
                $this->invalidateCache();

                // Update cache timestamp
                $this->cacheTimestamp = time();

                $this->alert = [
                    'type' => 'success',
                    'message' => 'برداشت با موفقیت حذف شد.'
                ];

                $this->confirmDeleteId = null;

                // Force refresh
                $this->dispatch('$refresh');
            } catch (\Exception $e) {
                DB::connection('sarafi')->rollBack();

                $this->alert = [
                    'type' => 'error',
                    'message' => 'خطا در حذف برداشت: ' . $e->getMessage()
                ];

                Log::error('Error deleting withdraw: ' . $e->getMessage());
            }
        }
    }

    public function showReport()
    {
        return redirect()->route('sarafi.withdraw-reports');
    }

    // Update currency safe
    private function updateCurrencySafe($adminId, $currency, $amount)
    {
        $safe = CurrencySafe::where('admin_id', $adminId)->lockForUpdate()->first();

        if (!$safe) {
            throw new \Exception('صندوق ارزی یافت نشد');
        }

        $column = strtolower($currency); // AFN -> afn

        if (!isset($safe->$column)) {
            throw new \Exception('ارز نامعتبر: ' . $currency);
        }

        $currentBalance = $safe->$column;
        $newBalance = $currentBalance + $amount;

        // بررسی موجودی کافی برای برداشت
        if ($amount < 0 && $newBalance < 0) {
            throw new \Exception('موجودی صندوق کافی نیست. موجودی فعلی ' . $currentBalance . ' ' . $currency);
        }

        $safe->$column = $newBalance;
        $safe->save();
    }

    // Print withdraw information
    public function print($id)
    {
        $withdraw = Withdraws::findOrFail($id);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [80, 210],
            'directionality' => 'rtl',
            'margin_top' => 2,
            'margin_bottom' => 2,
            'margin_left' => 2,
            'margin_right' => 2,
            'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                public_path('fonts'),
            ]),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'Shabnam' => [
                    'R' => 'Shabnam-FD.ttf',
                ],
            ],
            'default_font' => 'Shabnam',
            'tempDir' => storage_path('app/mpdf/tmp'),

        ]);

        $mpdf->SetAutoPageBreak(false);

        $html = view('pdf.Sarafi.withdraw-print', compact('withdraw'))->render();
        $mpdf->WriteHTML($html);

        $fileName = 'برداشت_' . ($withdraw->staff->name ?? 'کارمند') . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }

    // Invalidate cache for this admin
    private function invalidateCache()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // Clear specific cache keys
        $keys = [
            $this->cacheKeys['withdraws_list'] . $adminId,
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Also clear pagination cache
        $this->resetPage();
    }

    // Get paginated withdraws with intelligent caching
    public function getWithdrawsProperty()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // Create a unique cache key that includes timestamp
        $cacheKey = $this->cacheKeys['withdraws_list'] . $adminId . '_' .
            md5($this->search . $this->staff_id . $this->cacheTimestamp . request()->get('page', 1));

        // Use cache for better performance, but include timestamp for invalidation
        return Cache::remember($cacheKey, 60, function () use ($adminId) {
            $query = Withdraws::where('admin_id', $adminId)
                ->with('staff');

            // Apply search
            if ($this->search) {
                $query->where(function ($q) {
                    $q->whereHas('staff', function ($staffQuery) {
                        $staffQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('fathername', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%')
                            ->orWhere('job', 'like', '%' . $this->search . '%');
                    })
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('expanses_type', 'like', '%' . $this->search . '%');
                });
            }

            // Filter by staff if selected
            if ($this->staff_id) {
                $query->where('staff_id', $this->staff_id);
            }

            return $query->orderBy('created_at', 'desc')->paginate(10);
        });
    }

    // Get filtered staff for search
    public function getFilteredStaffsProperty()
    {
        if (empty($this->searchStaff)) {
            return collect();
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        return Staffs::where('admin_id', $adminId)
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchStaff . '%')
                    ->orWhere('fathername', 'like', '%' . $this->searchStaff . '%')
                    ->orWhere('phone', 'like', '%' . $this->searchStaff . '%')
                    ->orWhere('job', 'like', '%' . $this->searchStaff . '%');
            })
            ->limit(10)
            ->get();
    }

    // Get withdraw summary by currency for cards
    public function getWithdrawSummaryProperty()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $query = Withdraws::where('admin_id', $adminId);

        // Filter by staff if selected
        if ($this->staff_id) {
            $query->where('staff_id', $this->staff_id);
        }

        $withdraws = $query->get();

        $summary = [];
        foreach ($this->currencies as $currency) {
            $total = $withdraws->where('currency', $currency['code'])->sum('amount');
            $summary[$currency['name_fa']] = [
                'total' => $total,
                'code' => $currency['code']
            ];
        }

        return $summary;
    }

    // Get total withdraw in USD
    public function getTotalUsdProperty()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();

        if (!$latestProfitRate) {
            return 0;
        }

        $exchangeRates = [
            'AFN' => $latestProfitRate->afn_buy_cash ?? 66.2,
            'USD' => 1,
            'IRR' => $latestProfitRate->irr_buy_cash ?? 110000,
            'EUR' => $latestProfitRate->eur_buy_cash ?? 70,
            'PKR' => $latestProfitRate->pkr_buy_cash ?? 32,
            'AED' => $latestProfitRate->aed_buy_cash ?? 44,
            'TRY' => $latestProfitRate->try_buy_cash ?? 60,
            'CNY' => $latestProfitRate->cny_buy_cash ?? 43,
            'INR' => 7.14,
        ];

        $query = Withdraws::where('admin_id', $adminId);

        // Filter by staff if selected
        if ($this->staff_id) {
            $query->where('staff_id', $this->staff_id);
        }

        $withdraws = $query->get();

        $totalUsd = 0;
        foreach ($withdraws as $withdraw) {
            $rate = $exchangeRates[$withdraw->currency] ?? 1;
            if ($rate > 0) {
                $totalUsd += $withdraw->amount / $rate;
            }
        }

        return $totalUsd;
    }

    // Refresh data manually
    public function refreshData()
    {
        $this->cacheTimestamp = time();
        $this->invalidateCache();
        $this->alert = [
            'type' => 'success',
            'message' => 'داده‌ها با موفقیت بروزرسانی شدند.'
        ];
    }

    // Format amount on blur
    public function formatAmount()
    {
        if ($this->amount) {
            $this->formatted_amount = number_format($this->amount);
            $this->generateAmountInWords();
        }
    }

    public function render()
    {
        $withdraws = $this->withdraws;
        $withdrawSummary = $this->withdrawSummary;
        $totalUsd = $this->totalUsd;

        if (empty($this->staffs)) {
            $this->loadStaffs();
        }

        return view('livewire.sarafi.withdraw', compact(
            'withdraws',
            'withdrawSummary',
            'totalUsd',
        ))->with('staffs', $this->staffs);
    }

    protected $listeners = ['refreshStaffs' => 'loadStaffs'];

    // در هر عملیاتی که کارمندان تغییر می‌کنند، این event را dispatch کنید
    public function updatedStaffId($value)
    {
        if ($value) {
            $this->selectStaff($value);
        }
    }
}