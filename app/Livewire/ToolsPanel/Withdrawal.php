<?php

namespace App\Livewire\ToolsPanel;

use App\Models\Tools\ShopSafe;
use App\Models\Tools\User;
use App\Models\Tools\Withdrawals;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;

class Withdrawal extends Component
{
    public $currency = 'afn';
    public $type = 'کرایه'; 
    public $amount;
    public $amountInWords;
    public $date;
    public $description;

    public $withdrawalId;
    public $search = '';
    public $withdrawals = [];
    public $confirmDeleteId = null; 


    // کارت‌های آماری
    public $withdrawalStats = [
        'today' => ['afn' => 0, 'usd' => 0, 'toman' => 0, 'total' => 0],
        'week' => ['afn' => 0, 'usd' => 0, 'toman' => 0, 'total' => 0],
        'month' => ['afn' => 0, 'usd' => 0, 'toman' => 0, 'total' => 0],
        'total' => ['afn' => 0, 'usd' => 0, 'toman' => 0, 'total' => 0]
    ];

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->updateWithdrawals();
        $this->updateStats();
    }

    public function submitWithdrawal()
    {
        // پاک کردن کاما از مقدار قبل از اعتبارسنجی
        $cleanAmount = $this->cleanAmount($this->amount);
        
        // اعتبارسنجی با مقدار پاک شده
        $this->validate([
            'currency' => 'required|in:afn,usd,toman',
            'type' => 'required|string|max:255',
            'amount' => 'required',
            'date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ], [
            'amount.required' => 'مقدار برداشت الزامی است.',
            'type.required' => 'نوع برداشت الزامی است.',
        ]);

        // اعتبارسنجی دستی برای مقدار عددی
        if (!is_numeric($cleanAmount) || $cleanAmount <= 0) {
            session()->flash('error', 'مقدار باید یک عدد معتبر و بزرگتر از صفر باشد.');
            return;
        }

        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        // بررسی موجودی صندوق
        $safe = ShopSafe::where('user_id', $adminId)->first();
        if (!$safe) {
            session()->flash('error', 'صندوق ارزی یافت نشد!');
            return;
        }

        // استفاده از مقدار پاک شده
        $amountValue = (int)$cleanAmount;

        if ($amountValue > $safe->{$this->currency}) {
            $currencyName = $this->getCurrencyName($this->currency);
            session()->flash('error', "موجودی $currencyName کافی نیست! موجودی: " . number_format($safe->{$this->currency}));
            return;
        }

        $data = [
            'user_id' => $user->id,
            'admin_id' => $adminId,
            'currency' => $this->currency,
            'type' => $this->type, // اضافه کردن نوع برداشت
            'amount' => $amountValue,
            'date' => $this->date,
            'description' => $this->description,
        ];

        if ($this->withdrawalId) {
            // ویرایش برداشت
            $oldWithdrawal = Withdrawals::find($this->withdrawalId);
            
            // برگشت مبلغ قدیم به صندوق
            $safe->{$oldWithdrawal->currency} += $oldWithdrawal->amount;
            
            // کسر مبلغ جدید از صندوق
            $safe->{$this->currency} -= $amountValue;
            $safe->save();

            $oldWithdrawal->update($data);
            session()->flash('message', 'برداشت با موفقیت بروزرسانی شد.');
        } else {
            // ثبت برداشت جدید
            Withdrawals::create($data);
            
            // کسر از صندوق
            $safe->{$this->currency} -= $amountValue;
            $safe->save();

            session()->flash('message', 'برداشت با موفقیت ثبت شد.');
        }

        $this->updateWithdrawals();
        $this->updateStats();
        $this->resetForm();
    }

    private function cleanAmount($amount)
    {
        return str_replace(',', '', $amount);
    }

    private function getCurrencyName($currency)
    {
        $names = [
            'afn' => 'افغانی',
            'usd' => 'دالر',
            'toman' => 'تومان'
        ];
        return $names[$currency] ?? $currency;
    }

    private function updateStats()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        // امروز
        $today = Jalalian::now()->format('Y/m/d');
        $todayWithdrawals = Withdrawals::where('admin_id', $adminId)
            ->where('date', $today)
            ->get();

        $this->withdrawalStats['today']['afn'] = $todayWithdrawals->where('currency', 'afn')->sum('amount');
        $this->withdrawalStats['today']['usd'] = $todayWithdrawals->where('currency', 'usd')->sum('amount');
        $this->withdrawalStats['today']['toman'] = $todayWithdrawals->where('currency', 'toman')->sum('amount');
        $this->withdrawalStats['today']['total'] = $todayWithdrawals->sum('amount');

        // این هفته
        $startOfWeek = Jalalian::now()->subDays(7)->format('Y/m/d');
        $weekWithdrawals = Withdrawals::where('admin_id', $adminId)
            ->where('date', '>=', $startOfWeek)
            ->get();

        $this->withdrawalStats['week']['afn'] = $weekWithdrawals->where('currency', 'afn')->sum('amount');
        $this->withdrawalStats['week']['usd'] = $weekWithdrawals->where('currency', 'usd')->sum('amount');
        $this->withdrawalStats['week']['toman'] = $weekWithdrawals->where('currency', 'toman')->sum('amount');
        $this->withdrawalStats['week']['total'] = $weekWithdrawals->sum('amount');

        // این ماه
        $startOfMonth = Jalalian::now()->getFirstDayOfMonth()->format('Y/m/d');
        $monthWithdrawals = Withdrawals::where('admin_id', $adminId)
            ->where('date', '>=', $startOfMonth)
            ->get();

        $this->withdrawalStats['month']['afn'] = $monthWithdrawals->where('currency', 'afn')->sum('amount');
        $this->withdrawalStats['month']['usd'] = $monthWithdrawals->where('currency', 'usd')->sum('amount');
        $this->withdrawalStats['month']['toman'] = $monthWithdrawals->where('currency', 'toman')->sum('amount');
        $this->withdrawalStats['month']['total'] = $monthWithdrawals->sum('amount');

        // کل
        $totalWithdrawals = Withdrawals::where('admin_id', $adminId)->get();
        $this->withdrawalStats['total']['afn'] = $totalWithdrawals->where('currency', 'afn')->sum('amount');
        $this->withdrawalStats['total']['usd'] = $totalWithdrawals->where('currency', 'usd')->sum('amount');
        $this->withdrawalStats['total']['toman'] = $totalWithdrawals->where('currency', 'toman')->sum('amount');
        $this->withdrawalStats['total']['total'] = $totalWithdrawals->sum('amount');
    }

    private function resetForm()
    {
        $this->reset([
            'currency',
            'type',
            'amount',
            'amountInWords',
            'description',
            'withdrawalId',
        ]);
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->type = 'کرایه'; // بازگشت به مقدار پیش‌فرض
    }

    public function formatAmount()
    {
        if ($this->amount) {
            // پاک کردن کاماها و تبدیل به عدد
            $cleanAmount = $this->cleanAmount($this->amount);
            if (is_numeric($cleanAmount)) {
                $this->amount = number_format((int)$cleanAmount);
            }
        }
    }

      public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        $withdrawal = Withdrawals::findOrFail($this->confirmDeleteId);

        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        // برگشت مبلغ به صندوق
        $safe = ShopSafe::where('user_id', $adminId)->first();
        if ($safe) {
            $safe->{$withdrawal->currency} += $withdrawal->amount;
            $safe->save();
        }

        $withdrawal->delete();

        session()->flash('message', 'برداشت با موفقیت حذف شد.');

        $this->updateWithdrawals();
        $this->updateStats();
        $this->confirmDeleteId = null;
    }

    public function edit($id)
    {
        $withdrawal = Withdrawals::findOrFail($id);
        $this->withdrawalId = $id;
        $this->currency = $withdrawal->currency;
        $this->type = $withdrawal->type; // مقداردهی نوع برداشت
        $this->amount = number_format($withdrawal->amount);
        $this->date = $withdrawal->date;
        $this->description = $withdrawal->description;
    }

    public function updateWithdrawals()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        $this->withdrawals = Withdrawals::where('admin_id', $adminId)
            ->latest()
            ->get();
    }


       public function print($withdrawalId)
    {
        $withdrawal = Withdrawals::with(['user', 'admin'])->findOrFail($withdrawalId);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [80, 190],
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
        ]);

        $mpdf->SetAutoPageBreak(false);

        $html = view('pdf.Tools.withdrawal-print', compact('withdrawal'))->render();
        $mpdf->WriteHTML($html);

        $fileName = 'سند_برداشت_شماره_' . $withdrawal->id . '_' . $withdrawal->type . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }


    public function render()
    {
        return view('livewire.tools-panel.withdrawal');
    }
}