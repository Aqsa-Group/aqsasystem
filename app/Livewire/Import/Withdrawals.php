<?php

namespace App\Livewire\Import;

use App\Models\Import\Safe;
use App\Models\Import\User;
use App\Models\Import\Withdraw;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;

class Withdrawals extends Component
{
    public $currency = 'AFN';
    public $type = 'کرایه'; 
    public $amount;
    public $amountInWords;
    public $date;
    public $description;

    public $withdrawalId;
    public $search = '';
    public $withdrawals = [];
    public $confirmDeleteId = null; 


    public $withdrawalStats = [
        'today' => ['AFN' => 0, 'USD' => 0, 'toman' => 0, 'total' => 0],
        'week' => ['AFN' => 0, 'USD' => 0, 'toman' => 0, 'total' => 0],
        'month' => ['AFN' => 0, 'USD' => 0, 'toman' => 0, 'total' => 0],
        'total' => ['AFN' => 0, 'USD' => 0, 'toman' => 0, 'total' => 0]
    ];

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->updateWithdrawals();
        $this->updateStats();
    }

  public function submitWithdrawal()
{
    $cleanAmount = $this->cleanAmount($this->amount);
    
    $this->validate([
        'currency' => 'required|in:AFN,USD',
        'type' => 'required|string|max:255',
        'amount' => 'required',
        'date' => 'required|date',
        'description' => 'nullable|string|max:500',
    ], [
        'amount.required' => 'مقدار برداشت الزامی است.',
        'type.required' => 'نوع برداشت الزامی است.',
    ]);

    if (!is_numeric($cleanAmount) || floatval($cleanAmount) <= 0) {
        session()->flash('error', 'مقدار باید یک عدد معتبر و بزرگتر از صفر باشد.');
        return;
    }

    $user = Auth::guard('import')->user();
    $adminId = $user->admin_id ?? $user->id;

    $safe = Safe::where('user_id', $adminId)
        ->latest('id') 
        ->first();

    if (!$safe) {
        session()->flash('error', 'صندوق ارزی یافت نشد!');
        return;
    }

    session()->flash('debug', "selected safe id: {$safe->id}, {$this->currency} balance: " . number_format($safe->{$this->currency}));

    $amountValue = floatval($cleanAmount);

    $safeBalance = is_numeric($safe->{$this->currency}) ? floatval($safe->{$this->currency}) : 0;

    if ($amountValue > $safeBalance) {
        $currencyName = $this->getCurrencyName($this->currency);
        session()->flash('error', "موجودی $currencyName کافی نیست! موجودی: " . number_format($safeBalance));
        return;
    }

    $data = [
        'user_id' => $user->id,
        'admin_id' => $adminId,
        'currency' => $this->currency,
        'type' => $this->type,
        'amount' => $amountValue,
        'date' => $this->date,
        'description' => $this->description,
    ];

    if ($this->withdrawalId) {
        $oldWithdrawal = Withdraw::find($this->withdrawalId);
        if ($oldWithdrawal) {
            $oldCurrency = $oldWithdrawal->currency;
            $oldAmount = floatval($oldWithdrawal->amount);
            $safe->{$oldCurrency} = (is_numeric($safe->{$oldCurrency}) ? floatval($safe->{$oldCurrency}) : 0) + $oldAmount;

            $safe->{$this->currency} = (is_numeric($safe->{$this->currency}) ? floatval($safe->{$this->currency}) : 0) - $amountValue;
            $safe->save();

            $oldWithdrawal->update($data);
            session()->flash('message', 'برداشت با موفقیت بروزرسانی شد.');
        } else {
            session()->flash('error', 'برداشت قبلی یافت نشد.');
            return;
        }
    } else {
        Withdraw::create($data);

        $safe->{$this->currency} = (is_numeric($safe->{$this->currency}) ? floatval($safe->{$this->currency}) : 0) - $amountValue;
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
            'AFN' => 'دالر',
            'USD' => 'تومان'
        ];
        return $names[$currency] ?? $currency;
    }

 private function updateStats()
{
    $user = Auth::guard('import')->user();

    $today = Jalalian::now()->toCarbon()->toDateString();
    $todayWithdrawals = Withdraw::where('user_id', $user->id)
        ->whereDate('created_at', $today)
        ->get();

    $this->withdrawalStats['today']['AFN'] = $todayWithdrawals->where('currency', 'AFN')->sum('amount');
    $this->withdrawalStats['today']['USD'] = $todayWithdrawals->where('currency', 'USD')->sum('amount');
    $this->withdrawalStats['today']['total'] = $todayWithdrawals->sum('amount');

    // این هفته
    $startOfWeek = Jalalian::now()->subDays(7)->toCarbon()->toDateString();
    $weekWithdrawals = Withdraw::where('user_id', $user->id)
        ->whereDate('created_at', '>=', $startOfWeek)
        ->get();

    $this->withdrawalStats['week']['AFN'] = $weekWithdrawals->where('currency', 'AFN')->sum('amount');
    $this->withdrawalStats['week']['USD'] = $weekWithdrawals->where('currency', 'USD')->sum('amount');
    $this->withdrawalStats['week']['total'] = $weekWithdrawals->sum('amount');

    // این ماه
    $startOfMonth = Jalalian::now()->getFirstDayOfMonth()->toCarbon()->toDateString();
    $monthWithdrawals = Withdraw::where('user_id', $user->id)
        ->whereDate('created_at', '>=', $startOfMonth)
        ->get();

    $this->withdrawalStats['month']['AFN'] = $monthWithdrawals->where('currency', 'AFN')->sum('amount');
    $this->withdrawalStats['month']['USD'] = $monthWithdrawals->where('currency', 'USD')->sum('amount');
    $this->withdrawalStats['month']['total'] = $monthWithdrawals->sum('amount');

    // کل
    $totalWithdrawals = Withdraw::where('user_id', $user->id)->get();

    $this->withdrawalStats['total']['AFN'] = $totalWithdrawals->where('currency', 'AFN')->sum('amount');
    $this->withdrawalStats['total']['USD'] = $totalWithdrawals->where('currency', 'USD')->sum('amount');
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
        $this->type = 'کرایه'; 
    }

    public function formatAmount()
    {
        if ($this->amount) {
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
        $withdrawal = Withdraw::findOrFail($this->confirmDeleteId);

        $user = Auth::guard('import')->user();
        $adminId = $user->admin_id ?? $user->id;

        $safe = Safe::where('user_id', $adminId)->first();
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
        $withdrawal = Withdraw::findOrFail($id);
        $this->withdrawalId = $id;
        $this->currency = $withdrawal->currency;
        $this->type = $withdrawal->type; 
        $this->amount = number_format($withdrawal->amount);
        $this->description = $withdrawal->description;
    }

    public function updateWithdrawals()
    {
        $user = Auth::guard('import')->user();
        $adminId = $user->admin_id ?? $user->id;

        $this->withdrawals = Withdraw::where('user_id', $user->id)
            ->latest()
            ->get();
    }


       public function print($withdrawalId)
    {
        $withdrawal = Withdraw::with(['user'])->findOrFail($withdrawalId);

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

        $html = view('pdf.import.withdrawal-print', compact('withdrawal'))->render();
        $mpdf->WriteHTML($html);

        $fileName = 'سند_برداشت_شماره_' . $withdrawal->id . '_' . $withdrawal->type . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }


    public function render()
    {
        return view('livewire.import.withdrawals');
    }
}