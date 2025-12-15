<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Sarafi\User;
use App\Models\Sarafi\ProfitRate;
use App\Models\Sarafi\ChangerDeal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class SarafiReports extends Component
{
    public $search = '';
    public $selectedCurrency = '';
    public $accountType = '';
    public $date = '';
    public $specificSarafiId = '';

    public $reports = [];
    public $sarafis = [];

    public $currentSarafiId;
    public $currentSarafiName;

    public function mount()
    {
        $user = Auth::guard('sarafi')->user();
        $this->currentSarafiId = $user->id;
        $this->currentSarafiName = $user->sarafi_name;

        $this->sarafis = User::whereIn('role', ['admin', 'sarafi'])
            ->where('id', '!=', $this->currentSarafiId)
            ->select('id', 'sarafi_name')
            ->orderBy('sarafi_name')
            ->get()
            ->toArray();

        $this->generateReport();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'selectedCurrency', 'accountType', 'date', 'specificSarafiId'])) {
            $this->generateReport();
        }
    }

   public function generateReport()
{
    $latestProfitRate = ProfitRate::latest()->first();

    $reportData = [];

    $otherSarafis = $this->specificSarafiId
        ? collect($this->sarafis)->where('id', $this->specificSarafiId)->all()
        : $this->sarafis;

    $query = ChangerDeal::where(function ($q) {
        $q->where('from_sarafi', $this->currentSarafiId)
            ->orWhere('to_sarafi', $this->currentSarafiId);
    });

    if ($this->selectedCurrency) {
        $query->where('currency', $this->selectedCurrency);
    }

    if ($this->accountType) {
        $query->where('account_type', $this->accountType);
    }

    if ($this->date) {
        $persianDate = $this->convertToPersianDate($this->date);
        if ($persianDate) {
            $query->where('date', $persianDate);
        }
    }

    $transactions = $query->get();

    foreach ($otherSarafis as $sarafi) {
        $sarafiId = $sarafi['id'];
        $sarafiName = $sarafi['sarafi_name'];

        $balances = [];
        $detailedTransactions = [];

        // معاملات که صرافی جاری به صرافی دیگر فرستاده (ارسال)
        $sentToThisSarafi = $transactions
            ->where('from_sarafi', $this->currentSarafiId)
            ->where('to_sarafi', $sarafiId);

        foreach ($sentToThisSarafi as $transaction) {
            $currency = $transaction->currency;
            $amount = $transaction->amount;

            if (!isset($balances[$currency])) {
                $balances[$currency] = 0;
            }
            // تغییر: ارسال = موجودی مثبت (ما به آنها پول فرستادیم، پس آنها به ما بدهکار هستند)
            $balances[$currency] += $amount;

            $detailedTransactions[] = [
                'date' => $transaction->date,
                'type' => 'ارسال',
                'currency' => $currency,
                'currency_name' => $this->getPersianCurrencyName($currency),
                'amount' => $amount,
                'description' => $transaction->description,
                'account_type' => $transaction->account_type,
                'direction' => 'outgoing' // برای نمایش در گزارش
            ];
        }

        // معاملات که از صرافی دیگر دریافت کرده‌ایم (دریافت)
        $receivedFromThisSarafi = $transactions
            ->where('to_sarafi', $this->currentSarafiId)
            ->where('from_sarafi', $sarafiId);

        foreach ($receivedFromThisSarafi as $transaction) {
            $currency = $transaction->currency;
            $amount = $transaction->amount;

            if (!isset($balances[$currency])) {
                $balances[$currency] = 0;
            }
            // تغییر: دریافت = موجودی منفی (ما از آنها پول دریافت کردیم، پس ما به آنها بدهکار هستیم)
            $balances[$currency] -= $amount;

            $detailedTransactions[] = [
                'date' => $transaction->date,
                'type' => 'دریافت',
                'currency' => $currency,
                'currency_name' => $this->getPersianCurrencyName($currency),
                'amount' => $amount,
                'description' => $transaction->description,
                'account_type' => $transaction->account_type,
                'direction' => 'incoming' // برای نمایش در گزارش
            ];
        }

        // حذف ارزهایی که موجودی صفر دارند
        foreach ($balances as $currency => $balance) {
            if ($balance == 0) {
                unset($balances[$currency]);
            }
        }

        $totalBalance = $this->calculateTotalBalance($balances, $this->accountType);

        if (!empty($balances) || !empty($detailedTransactions)) {
            $reportData[$sarafiId] = [
                'sarafi_id' => $sarafiId,
                'sarafi_name' => $sarafiName,
                'balances' => $balances,
                'total_balance' => $totalBalance,
                'transactions' => $detailedTransactions,
                'balance_summary' => $this->getBalanceSummary($balances)
            ];
        }
    }

    if ($this->search) {
        $searchTerm = strtolower($this->search);
        $reportData = array_filter($reportData, function ($sarafi) use ($searchTerm) {
            return str_contains(strtolower($sarafi['sarafi_name']), $searchTerm);
        });
    }

    $this->reports = array_values($reportData);
}



private function getBalanceSummary($balances)
{
    $summary = [
        'total_positive' => 0,
        'total_negative' => 0,
        'currencies' => []
    ];

    foreach ($balances as $currency => $balance) {
        $summary['currencies'][$currency] = [
            'amount' => $balance,
            'status' => $balance > 0 ? 'طلبات از ما' : 'بدهی به ما',
            'persian_name' => $this->getPersianCurrencyName($currency)
        ];

        if ($balance > 0) {
            $summary['total_positive'] += $balance;
        } else {
            $summary['total_negative'] += abs($balance);
        }
    }

    return $summary;
}



private function formatBalanceDisplay($balances)
{
    $result = [];
    foreach ($balances as $currency => $balance) {
        $formatted = number_format(abs($balance), 2);
        $status = $balance > 0 ? 'طلبات از ما' : 'بدهی به ما';
        $sign = $balance > 0 ? '+' : '-';
        
        $result[] = [
            'currency' => $currency,
            'persian_name' => $this->getPersianCurrencyName($currency),
            'amount' => $formatted,
            'raw_amount' => $balance,
            'status' => $status,
            'display' => "{$sign}{$formatted} {$this->getPersianCurrencyName($currency)} ({$status})"
        ];
    }
    return $result;
}




  private function calculateTotalBalance($balances, $accountType = '')
{
    $latestProfitRate = ProfitRate::latest()->first();

    $rateType = 'cash';
    if ($accountType == 'بانکی') {
        $rateType = 'bank';
    }

    if (!$latestProfitRate) {
        $exchangeRates = [
            'afn' => 66.20,
            'usd' => 1,
            'irr' => 110000.00,
            'eur' => 0.93,
            'pkr' => 277.78,
            'aed' => 3.67,
            'try' => 32.26,
            'cny' => 7.24,
        ];
    } else {
        if ($rateType == 'cash') {
            $exchangeRates = [
                'afn' => $latestProfitRate->afn_buy_cash ?: 66.20,
                'usd' => $latestProfitRate->usd_buy_cash ?: 1,
                'irr' => $latestProfitRate->irr_buy_cash ?: 110000.00,
                'eur' => $latestProfitRate->eur_buy_cash ?: 0.93,
                'pkr' => $latestProfitRate->pkr_buy_cash ?: 277.78,
                'aed' => $latestProfitRate->aed_buy_cash ?: 3.67,
                'try' => $latestProfitRate->try_buy_cash ?: 32.26,
                'cny' => $latestProfitRate->cny_buy_cash ?: 7.24,
            ];
        } else {
            $exchangeRates = [
                'afn' => $latestProfitRate->afn_buy_bank ?: 66.20,
                'usd' => $latestProfitRate->usd_buy_bank ?: 1,
                'irr' => $latestProfitRate->irr_buy_bank ?: 110000.00,
                'eur' => $latestProfitRate->eur_buy_bank ?: 0.93,
                'pkr' => $latestProfitRate->pkr_buy_bank ?: 277.78,
                'aed' => $latestProfitRate->aed_buy_bank ?: 3.67,
                'try' => $latestProfitRate->try_buy_bank ?: 32.26,
                'cny' => $latestProfitRate->cny_buy_bank ?: 7.24,
            ];
        }
    }

    $total = 0;

    foreach ($balances as $currency => $balance) {
        if (isset($exchangeRates[$currency]) && $exchangeRates[$currency] > 0) {
            // استفاده از مقدار خام balance (که می‌تواند مثبت یا منفی باشد)
            $total += $balance / $exchangeRates[$currency];
        }
    }

    return $total;
}

    public function refreshReport()
    {
        $this->generateReport();
        session()->flash('message', 'گزارش با موفقیت بروزرسانی شد.');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->specificSarafiId = '';
        $this->selectedCurrency = '';
        $this->accountType = '';
        $this->date = '';
        $this->generateReport();
        session()->flash('message', 'فیلترها با موفقیت بازنشانی شدند.');
    }

    public function printReport()
    {
        $printData = $this->preparePrintData();
        $this->dispatch('open-print-modal', printData: $printData);
    }

    public function downloadPDF()
    {
        $printData = $this->preparePrintData();

        // تنظیمات mPDF برای A4 Portrait
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-P', // A4 Portrait
            'directionality' => 'rtl', // RTL برای فارسی
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 10,
            'margin_right' => 10,
            'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                public_path('fonts'),
            ]),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [

                'vazir' => [
                    'R' => 'Vazir.ttf',
                    'B' => 'Vazir-Bold.ttf',
                    'I' => 'Vazir-Light.ttf',
                    'BI' => 'Vazir-Medium.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'vazir',
            'tempDir' => storage_path('temp/mpdf'),
        ]);

        // CSS استایل برای PDF
        $stylesheet = '
            <style>
                body {
                    font-family: shabnam, sans-serif;
                    font-size: 10pt;
                    direction: rtl;
                    text-align: right;
                    line-height: 1.4;
                }
                
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                    padding-bottom: 10px;
                    border-bottom: 2px solid #000;
                }
                
                .header h1 {
                    font-size: 16pt;
                    margin: 0 0 10px 0;
                    font-weight: bold;
                }
                
                .header p {
                    margin: 5px 0;
                    font-size: 11pt;
                }
                
                .filters {
                    margin-bottom: 20px;
                    padding: 10px;
                    background-color: #f5f5f5;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                
                .filter-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 8px;
                }
                
                .filter-item {
                    display: flex;
                    justify-content: space-between;
                }
                
                .filter-label {
                    font-weight: bold;
                }
                
                .section-title {
                    font-size: 13pt;
                    font-weight: bold;
                    text-align: center;
                    margin: 15px 0 10px 0;
                    padding-bottom: 5px;
                    border-bottom: 1px solid #ccc;
                }
                
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 10px 0;
                    font-size: 9pt;
                }
                
                th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                    text-align: center;
                    padding: 8px 4px;
                    border: 1px solid #000;
                }
                
                td {
                    padding: 6px 4px;
                    border: 1px solid #000;
                    text-align: center;
                }
                
                .text-right {
                    text-align: right;
                }
                
                .text-center {
                    text-align: center;
                }
                
                .text-left {
                    text-align: left;
                }
                
                .positive {
                    color: #006400;
                    font-weight: bold;
                }
                
                .negative {
                    color: #8B0000;
                    font-weight: bold;
                }
                
                .summary {
                    margin-top: 20px;
                    padding: 15px;
                    background-color: #f8f9fa;
                    border: 1px solid #dee2e6;
                }
                
                .summary-info {
                    display: flex;
                    justify-content: space-between;
                }
                
                .footer {
                    margin-top: 30px;
                    padding-top: 10px;
                    border-top: 1px solid #ccc;
                    text-align: center;
                    font-size: 9pt;
                    color: #666;
                }
                
                .col-no { width: 30px; }
                .col-sarafi { width: 120px; }
                .col-currency { width: 70px; }
                .col-total { width: 90px; }
                .col-date { width: 80px; }
                .col-type { width: 60px; }
                .col-amount { width: 90px; }
                .col-account { width: 60px; }
                .col-desc { width: 150px; }
            </style>
        ';

        // HTML محتوا
        $html = $stylesheet . $this->generatePDFContent($printData);

        $mpdf->WriteHTML($html);

        $fileName = 'گزارش-بیلانس-صرافی-' . Carbon::now()->format('Y-m-d') . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }

    private function generatePDFContent($printData)
    {
        $content = '
        <div class="header">
            <h1>' . $printData['title'] . '</h1>
            <p>تاریخ چاپ: ' . $printData['print_date'] . '</p>
            <p>صرافی جاری: ' . $printData['current_sarafi'] . '</p>
        </div>
        
       
        </div>';

        // اگر فقط یک صرافی انتخاب شده، جزئیات تراکنش‌ها
        if (count($printData['reports']) === 1) {
            $report = $printData['reports'][0];
            $content .= '
            <div class="section-title">جزئیات معاملات با ' . $report['sarafi_name'] . '</div>
            <table>
                <thead>
                    <tr>
                        <th class="col-no">#</th>
                        <th class="col-date">تاریخ</th>
                        <th class="col-type">نوع</th>
                        <th class="col-currency">ارز</th>
                        <th class="col-amount">مبلغ</th>
                        <th class="col-account">نوع حساب</th>
                        <th class="col-desc">توضیحات</th>
                    </tr>
                </thead>
                <tbody>';

            if (!empty($report['transactions'])) {
                foreach ($report['transactions'] as $index => $transaction) {
                    $colorClass = $transaction['type'] === 'ارسال' ? '' : 'positive';
                    $content .= '
                    <tr>
                        <td class="col-no text-center">' . ($index + 1) . '</td>
                        <td class="col-date text-center">' . $transaction['date'] . '</td>
                        <td class="col-type text-center ' . $colorClass . '">' . $transaction['type'] . '</td>
                        <td class="col-currency text-center">' . $transaction['currency_name'] . '</td>
                        <td class="col-amount text-center ' . $colorClass . '">' . number_format($transaction['amount'], 2) . '</td>
                        <td class="col-account text-center">' . ($transaction['account_type'] ?? '-') . '</td>
                        <td class="col-desc text-right">' . ($transaction['description'] ?? '-') . '</td>
                    </tr>';
                }
            } else {
                $content .= '
                    <tr>
                        <td colspan="7" class="text-center">هیچ تراکنشی یافت نشد</td>
                    </tr>';
            }

            $content .= '
                </tbody>
            </table>';
        }

        // جدول خلاصه موجودی‌ها
        $content .= '
        <div class="section-title">خلاصه موجودی‌ها</div>
        <table>
            <thead>
                <tr>
                    <th class="col-no">#</th>
                    <th class="col-sarafi">صرافی</th>
                    <th class="col-currency">دالر</th>
                    <th class="col-currency">افغانی</th>
                    <th class="col-currency">تومان</th>
                    <th class="col-currency">کلدار</th>
                    <th class="col-currency">یورو</th>
                    <th class="col-currency">درهم</th>
                    <th class="col-currency">لیره</th>
                    <th class="col-currency">یوان</th>
                    <th class="col-total">مجموع به دالر</th>
                </tr>
            </thead>
            <tbody>';

        if (!empty($printData['reports'])) {
            foreach ($printData['reports'] as $index => $report) {
                $content .= '
                <tr>
                    <td class="col-no text-center">' . ($index + 1) . '</td>
                    <td class="col-sarafi text-center">' . $report['sarafi_name'] . '</td>
                    <td class="col-currency text-center ' . (($report['balances']['usd'] ?? 0) < 0 ? 'negative' : (($report['balances']['usd'] ?? 0) > 0 ? 'positive' : '')) . '">' . number_format($report['balances']['usd'] ?? 0, 2) . '</td>
                    <td class="col-currency text-center ' . (($report['balances']['afn'] ?? 0) < 0 ? 'negative' : (($report['balances']['afn'] ?? 0) > 0 ? 'positive' : '')) . '">' . number_format($report['balances']['afn'] ?? 0, 2) . '</td>
                    <td class="col-currency text-center ' . (($report['balances']['irr'] ?? 0) < 0 ? 'negative' : (($report['balances']['irr'] ?? 0) > 0 ? 'positive' : '')) . '">' . number_format($report['balances']['irr'] ?? 0, 2) . '</td>
                    <td class="col-currency text-center ' . (($report['balances']['pkr'] ?? 0) < 0 ? 'negative' : (($report['balances']['pkr'] ?? 0) > 0 ? 'positive' : '')) . '">' . number_format($report['balances']['pkr'] ?? 0, 2) . '</td>
                    <td class="col-currency text-center ' . (($report['balances']['eur'] ?? 0) < 0 ? 'negative' : (($report['balances']['eur'] ?? 0) > 0 ? 'positive' : '')) . '">' . number_format($report['balances']['eur'] ?? 0, 2) . '</td>
                    <td class="col-currency text-center ' . (($report['balances']['aed'] ?? 0) < 0 ? 'negative' : (($report['balances']['aed'] ?? 0) > 0 ? 'positive' : '')) . '">' . number_format($report['balances']['aed'] ?? 0, 2) . '</td>
                    <td class="col-currency text-center ' . (($report['balances']['try'] ?? 0) < 0 ? 'negative' : (($report['balances']['try'] ?? 0) > 0 ? 'positive' : '')) . '">' . number_format($report['balances']['try'] ?? 0, 2) . '</td>
                    <td class="col-currency text-center ' . (($report['balances']['cny'] ?? 0) < 0 ? 'negative' : (($report['balances']['cny'] ?? 0) > 0 ? 'positive' : '')) . '">' . number_format($report['balances']['cny'] ?? 0, 2) . '</td>
                    <td class="col-total text-center ' . ($report['total_balance'] < 0 ? 'negative' : ($report['total_balance'] > 0 ? 'positive' : '')) . '">' . number_format($report['total_balance'], 2) . '</td>
                </tr>';
            }
        } else {
            $content .= '
                <tr>
                    <td colspan="11" class="text-center">هیچ داده‌ای یافت نشد</td>
                </tr>';
        }

        $content .= '
            </tbody>
        </table>
    
        
        <div class="footer">
           
        </div>';

        return $content;
    }

    private function preparePrintData()
    {
        $selectedSarafiName = $this->specificSarafiId
            ? User::find($this->specificSarafiId)?->sarafi_name
            : 'همه';

        $printData = [
            'title' => 'گزارش بدهی/طلبی با سایر صرافی‌ها',
            'print_date' => Carbon::now()->format('Y/m/d H:i'),
            'current_sarafi' => $this->currentSarafiName,
            'filters' => [
                'صرافی' => $selectedSarafiName,
                'ارز' => $this->selectedCurrency ? $this->getPersianCurrencyName($this->selectedCurrency) : 'همه',
                'نوع حساب' => $this->accountType ?: 'همه',
                'تاریخ' => $this->date ?: 'همه'
            ],
            'reports' => $this->reports,
            'total_sarafis' => count($this->reports),
            'total_balance' => collect($this->reports)->sum('total_balance')
        ];

        return $printData;
    }

    private function convertToPersianDate($date)
    {
        try {
            if (str_contains($date, '/')) {
                return str_replace('/', '-', $date);
            }
            return $date;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getPersianCurrencyName($currencyCode)
    {
        $currencyMap = [
            'afn' => 'افغانی',
            'usd' => 'دالر',
            'irr' => 'تومان',
            'eur' => 'یورو',
            'pkr' => 'کلدار',
            'aed' => 'درهم',
            'try' => 'لیره',
            'cny' => 'یوان',
            'gbp' => 'پوند',
            'jpy' => 'ین',
            'sar' => 'ریال سعودی',
            'inr' => 'روپیه',
        ];

        $currencyCode = strtolower($currencyCode ?? 'usd');
        return $currencyMap[$currencyCode] ?? $currencyCode;
    }

    public function render()
    {
        return view('livewire.sarafi.sarafi-reports');
    }
}
