<?php

namespace App\Livewire\Market;

use App\Models\Market\Accounting;
use App\Models\Market\Shopkeeper;
use App\Models\Market\Shop;
use App\Models\Market\Market;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class ShopkeeperProfile extends Component
{
    use WithPagination;

    public $search = '';
    public $filterShopkeeperId = '';
    public $filterMarketId = '';
    public $filterExpansesType = '';
    public $filterCurrency = '';
    public $filterTransactionType = 'all';

    public $startDate = '';
    public $endDate = '';

    public $summary = [];
    public $shopkeepers = [];
    public $markets = [];
    public $expansesTypes = [];
    public $currencies = [];
        public $perPage = 10;


    protected $listeners = ['refreshReport' => 'generateReport'];
        protected $queryString = ['perPage'];


    public function mount()
    {
        $adminId = $this->getAdminId();

        $this->currencies = $this->getCurrencies();

        $this->shopkeepers = Shopkeeper::query()
            ->where('admin_id', $adminId)
            ->orderBy('fullname')
            ->get(['id', 'fullname'])
            ->toArray();

        $this->markets = Market::query()
            ->where('admin_id', $adminId)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();

        $this->expansesTypes = [
            'پول برق',
            'کرایه',
            'پول آب',
            'صفایی',
            'تحت الملکی',
        ];

        $this->generateReport();
    }

    private function getAdminId()
    {
        $user = Auth::guard('market')->user();
        return $user->role === 'admin' ? $user->id : $user->admin_id;
    }

    private function getCurrencies()
    {
        $adminId = $this->getAdminId();

        $currencies = Accounting::query()
            ->where('admin_id', $adminId)
            ->whereNotNull('currency')
            ->where('currency', '<>', '')
            ->distinct()
            ->orderBy('currency')
            ->pluck('currency')
            ->toArray();

        if (empty($currencies)) {
            $currencies = ['AFN', 'USD'];
        }

        $map = [
            'AFN' => 'افغانی',
            'USD' => 'دالر',
            'EUR' => 'یورو',
            'IRR' => 'تومان',
            'PKR' => 'کلدار',
            'AED' => 'درهم',
            'TRY' => 'لیره',
            'CNY' => 'یوان',
            'GBP' => 'پوند',
            'JPY' => 'ین',
            'SAR' => 'ریال سعودی',
            'INR' => 'روپیه',
        ];

        $result = [];
        foreach ($currencies as $code) {
            $result[$code] = $map[$code] ?? $code;
        }

        return $result;
    }

    private function getDateRange(): array
    {
        $start = null;
        $end = null;

        if ($this->startDate !== '') {
            try {
                $start = Jalalian::fromFormat(
                    'Y/m/d',
                    str_replace('-', '/', $this->startDate)
                )->toCarbon()->startOfDay();
            } catch (\Throwable $e) {
                $start = null;
            }
        }

        if ($this->endDate !== '') {
            try {
                $end = Jalalian::fromFormat(
                    'Y/m/d',
                    str_replace('-', '/', $this->endDate)
                )->toCarbon()->endOfDay();
            } catch (\Throwable $e) {
                $end = null;
            }
        }

        return [$start, $end];
    }

    private function buildTransactionsQuery(bool $withJoins = false)
    {
        $adminId = $this->getAdminId();
        [$startDateCarbon, $endDateCarbon] = $this->getDateRange();

        if ($withJoins) {
            $query = Accounting::query()
                ->leftJoin('shopkeepers', 'accountings.shopkeeper_id', '=', 'shopkeepers.id')
                ->leftJoin('shops', 'accountings.shop_id', '=', 'shops.id')
                ->leftJoin('markets', 'accountings.market_id', '=', 'markets.id')
                ->where('accountings.admin_id', $adminId)
                ->where(function ($q) {
                    $q->whereNotNull('accountings.shopkeeper_id')
                        ->orWhereNotNull('accountings.shop_id')
                        ->orWhereNotNull('accountings.booth_id');
                });
        } else {
            $query = Accounting::query()
                ->where('admin_id', $adminId)
                ->where(function ($q) {
                    $q->whereNotNull('shopkeeper_id')
                        ->orWhereNotNull('shop_id')
                        ->orWhereNotNull('booth_id');
                });
        }

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';

            if ($withJoins) {
                $query->where(function ($q) use ($search) {
                    $q->where('shopkeepers.fullname', 'like', $search)
                        ->orWhere('shops.number', 'like', $search)
                        ->orWhere('markets.name', 'like', $search);
                });
            } else {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('shopkeeper', function ($sq) use ($search) {
                        $sq->where('fullname', 'like', $search);
                    })->orWhereHas('shop', function ($sq) use ($search) {
                        $sq->where('number', 'like', $search);
                    })->orWhereHas('market', function ($sq) use ($search) {
                        $sq->where('name', 'like', $search);
                    });
                });
            }
        }

        if ($this->filterShopkeeperId !== '') {
            $query->where(
                $withJoins ? 'accountings.shopkeeper_id' : 'shopkeeper_id',
                $this->filterShopkeeperId
            );
        }

        if ($this->filterMarketId !== '') {
            $query->where(
                $withJoins ? 'accountings.market_id' : 'market_id',
                $this->filterMarketId
            );
        }

        if ($this->filterExpansesType !== '') {
            $query->where(
                $withJoins ? 'accountings.expanses_type' : 'expanses_type',
                $this->filterExpansesType
            );
        }

        if ($this->filterCurrency !== '') {
            $query->where(
                $withJoins ? 'accountings.currency' : 'currency',
                $this->filterCurrency
            );
        }

        if ($this->filterTransactionType === 'income') {
            $query->where(
                $withJoins ? 'accountings.paid' : 'paid',
                '>',
                0
            );
        } elseif ($this->filterTransactionType === 'expense') {
            $query->where(
                $withJoins ? 'accountings.paid' : 'paid',
                '<',
                0
            );
        }

        if ($startDateCarbon) {
            $query->where(
                $withJoins ? 'accountings.created_at' : 'created_at',
                '>=',
                $startDateCarbon
            );
        }

        if ($endDateCarbon) {
            $query->where(
                $withJoins ? 'accountings.created_at' : 'created_at',
                '<=',
                $endDateCarbon
            );
        }

        return $query;
    }

    public function generateReport()
    {
        $adminId = $this->getAdminId();

        $allowedTypes = [
            'پول برق',
            'کرایه',
            'پول آب',
            'صفایی',
            'تحت الملکی',
        ];

        $latestQuery = Accounting::query()
            ->where('admin_id', $adminId)
            ->where(function ($q) {
                $q->whereNotNull('shopkeeper_id')
                    ->orWhereNotNull('shop_id')
                    ->orWhereNotNull('booth_id');
            })
            ->whereIn('expanses_type', $allowedTypes);

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';

            $latestQuery->where(function ($q) use ($search) {
                $q->whereHas('shopkeeper', function ($sq) use ($search) {
                    $sq->where('fullname', 'like', $search);
                })->orWhereHas('shop', function ($sq) use ($search) {
                    $sq->where('number', 'like', $search);
                })->orWhereHas('market', function ($sq) use ($search) {
                    $sq->where('name', 'like', $search);
                });
            });
        }

        if ($this->filterShopkeeperId !== '') {
            $latestQuery->where('shopkeeper_id', $this->filterShopkeeperId);
        }

        if ($this->filterMarketId !== '') {
            $latestQuery->where('market_id', $this->filterMarketId);
        }

        if ($this->filterExpansesType !== '') {
            $latestQuery->where('expanses_type', $this->filterExpansesType);
        }

        if ($this->filterCurrency !== '') {
            $latestQuery->where('currency', $this->filterCurrency);
        }

        $subQuery = (clone $latestQuery)
            ->selectRaw('MAX(id) as id')
            ->groupBy(
                'shopkeeper_id',
                'shop_id',
                'market_id',
                'expanses_type'
            );

        $latestRecords = Accounting::query()
            ->whereIn('accountings.id', $subQuery)
            ->leftJoin('shopkeepers', 'accountings.shopkeeper_id', '=', 'shopkeepers.id')
            ->leftJoin('shops', 'accountings.shop_id', '=', 'shops.id')
            ->leftJoin('markets', 'accountings.market_id', '=', 'markets.id')
            ->select([
                'accountings.shopkeeper_id',
                'accountings.shop_id',
                'accountings.market_id',
                'accountings.expanses_type',
                'accountings.remained',
                'shopkeepers.fullname as shopkeeper_name',
                'shops.number as shop_number',
                'markets.name as market_name',
            ])
            ->get();

        $summary = [];

        foreach ($latestRecords as $record) {
            $key = ($record->shopkeeper_id ?? 0)
                . '_' . ($record->shop_id ?? 0)
                . '_' . ($record->market_id ?? 0);

            if (!isset($summary[$key])) {
                $summary[$key] = [
                    'shopkeeper_name' => $record->shopkeeper_name ?: 'نامشخص',
                    'shop_number' => $record->shop_number ?: 'نامشخص',
                    'market_name' => $record->market_name ?: 'نامشخص',
                    'shopkeeper_id' => $record->shopkeeper_id,
                    'shop_id' => $record->shop_id,
                    'market_id' => $record->market_id,
                    'balances' => [],
                ];
            }

            $summary[$key]['balances'][$record->expanses_type] =
                (float) $record->remained;
        }

        $summary = array_values($summary);

        usort($summary, static function ($a, $b) {
            return strcmp($a['shopkeeper_name'], $b['shopkeeper_name']);
        });

        $this->summary = $summary;
    }

    public function getTransactionsPaginated()
    {
        $query = $this->buildTransactionsQuery(false);

        $perPage = $this->perPage === 'all'
            ? max(1, $query->count())
            : max(1, (int) $this->perPage);

        $transactions = $query
            ->select([
                'id',
                'shopkeeper_id',
                'shop_id',
                'market_id',
                'expanses_type',
                'paid',
                'currency',
                'remained',
                'created_at',
                'type',
            ])
            ->with([
                'shopkeeper:id,fullname',
                'shop:id,number',
                'market:id,name',
            ])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($perPage);

        $transactions->setCollection(
            $transactions->getCollection()->map(
                static function ($item) {
                    return [
                        'id' => $item->id,
                        'shopkeeper_name' => $item->shopkeeper?->fullname ?? 'نامشخص',
                        'shop_number' => $item->shop?->number ?? 'نامشخص',
                        'market_name' => $item->market?->name ?? 'نامشخص',
                        'expanses_type' => $item->expanses_type ?: '-',
                        'paid' => (float) $item->paid,
                        'currency' => $item->currency,
                        'remained' => (float) $item->remained,
                        'created_at' => $item->created_at,
                        'date_fa' => $item->created_at
                            ? Jalalian::fromCarbon($item->created_at)->format('Y/m/d H:i')
                            : '-',
                        'type' => $item->type ?? '-',
                    ];
                }
            )
        );

        return $transactions;
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }



    // ==================== متدهای به‌روزرسانی خودکار ====================
    public function updatedSearch()
    {
        $this->resetPage();
        $this->generateReport();
    }

    public function updatedFilterShopkeeperId()
    {
        $this->resetPage();
        $this->generateReport();
    }

    public function updatedFilterMarketId()
    {
        $this->resetPage();
        $this->generateReport();
    }

    public function updatedFilterExpansesType()
    {
        $this->resetPage();
        $this->generateReport();
    }

    public function updatedFilterCurrency()
    {
        $this->resetPage();
        $this->generateReport();
    }

    public function updatedFilterTransactionType()
    {
        $this->resetPage();
        $this->generateReport();
    }

    public function updatedStartDate()
    {
        $this->resetPage();
        $this->generateReport();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
        $this->generateReport();
    }

    // ==================== دکمه‌های کنترلی ====================
    public function resetFilters()
    {
        $this->search = '';
        $this->filterShopkeeperId = '';
        $this->filterMarketId = '';
        $this->filterExpansesType = '';
        $this->filterCurrency = '';
        $this->filterTransactionType = 'all';
        $this->startDate = '';
        $this->endDate = '';
        $this->resetPage();
        $this->generateReport();
        session()->flash('message', 'فیلترها بازنشانی شدند.');
    }

    public function refreshReport()
    {
        $this->resetPage();
        $this->generateReport();
        session()->flash('message', 'گزارش به‌روزرسانی شد.');
    }
// ==================== دریافت تمام تراکنش‌ها برای PDF - نسخه سریع ====================
public function getAllTransactions()
{
    return $this->buildTransactionsQuery(true)
        ->select([
            'accountings.id',
            'accountings.expanses_type',
            'accountings.paid',
            'accountings.currency',
            'accountings.remained',
            'accountings.created_at',
            'accountings.type',
            'shopkeepers.fullname as shopkeeper_name',
            'shops.number as shop_number',
            'markets.name as market_name',
        ])
        ->orderByDesc('accountings.created_at')
        ->orderByDesc('accountings.id')
        ->get()
        ->map(static function ($item) {
            return [
                'id' => $item->id,
                'shopkeeper_name' => $item->shopkeeper_name ?: 'نامشخص',
                'shop_number' => $item->shop_number ?: 'نامشخص',
                'market_name' => $item->market_name ?: 'نامشخص',
                'expanses_type' => $item->expanses_type ?: '-',
                'paid' => (float) $item->paid,
                'currency' => $item->currency,
                'remained' => (float) $item->remained,
                'created_at' => $item->created_at,
                'date_fa' => $item->created_at
                    ? Jalalian::fromCarbon($item->created_at)->format('Y/m/d H:i')
                    : '-',
                'type' => $item->type ?? '-',
            ];
        })
        ->values();
}


// ==================== تولید PDF - نسخه سریع ====================
public function printReport()
{
    try {
        ini_set('memory_limit', '768M');
        ini_set('pcre.backtrack_limit', '100000000');
        ini_set('pcre.recursion_limit', '100000000');
        set_time_limit(300);

        $summary = $this->summary;
        $expansesTypes = $this->expansesTypes;
        $transactions = $this->getAllTransactions();

        $filterInfo = [
            'shopkeeper' => 'همه',
            'market' => 'همه',
            'expanses_type' => $this->filterExpansesType ?: 'همه',
            'currency' => $this->filterCurrency
                ? ($this->currencies[$this->filterCurrency]
                    ?? $this->filterCurrency)
                : 'همه',
            'type' => match ($this->filterTransactionType) {
                'income' => 'دریافتی‌ها',
                'expense' => 'برداشت‌ها',
                default => 'همه',
            },
            'startDate' => $this->startDate ?: 'نامحدود',
            'endDate' => $this->endDate ?: 'نامحدود',
        ];

        if ($this->filterShopkeeperId !== '') {
            $filterInfo['shopkeeper'] = Shopkeeper::query()
                ->whereKey($this->filterShopkeeperId)
                ->value('fullname') ?? 'همه';
        }

        if ($this->filterMarketId !== '') {
            $filterInfo['market'] = Market::query()
                ->whereKey($this->filterMarketId)
                ->value('name') ?? 'همه';
        }

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();

        $fontDir = $defaultConfig['fontDir'];
        $fontData = $defaultFontConfig['fontdata'];
        $fontDir[] = public_path('fonts/vazir/');

        $fontData['vazir'] = [
            'R' => 'Vazir-Light.ttf',
            'B' => 'Vazir-Bold.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'directionality' => 'rtl',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 8,
            'margin_right' => 8,
            'fontDir' => $fontDir,
            'fontdata' => $fontData,
            'default_font' => 'vazir',
            'tempDir' => storage_path('app/mpdf'),
            'simpleTables' => true,
            'packTableData' => true,
            'shrink_tables_to_fit' => 1,
            'autoScriptToLang' => false,
            'autoLangToFont' => false,
            'allow_charset_conversion' => false,
            'use_kwarray' => true,
        ]);

        $mpdf->SetAutoPageBreak(true, 10);

        $html = view('print.shopkeeper-profile-pdf', [
            'summary' => $summary,
            'expansesTypes' => $expansesTypes,
            'transactions' => $transactions,
            'filterInfo' => $filterInfo,
        ])->render();

        $mpdf->WriteHTML($html);

        $fileName = 'گزارش_دوکانداران_'
            . Jalalian::now()->format('Y_m_d_H_i_s')
            . '.pdf';

        $directory = storage_path('app/public');

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $path = $directory . DIRECTORY_SEPARATOR . $fileName;

        $mpdf->Output($path, 'F');

        $this->dispatch(
            'print-pdf',
            url: asset('storage/' . $fileName)
        );
    } catch (\Throwable $e) {
        Log::error(
            'PDF generation error for shopkeeper profile: ' . $e->getMessage(),
            [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]
        );

        session()->flash(
            'error',
            'خطا در ایجاد PDF: ' . $e->getMessage()
        );
    }
}

    public function render()
    {
        return view('livewire.market.shopkeeper-profile', [
            'shopkeepers' => $this->shopkeepers,
            'markets' => $this->markets,
            'expansesTypes' => $this->expansesTypes,
            'currencies' => $this->currencies,
            'summary' => $this->summary,
            'transactions' => $this->getTransactionsPaginated(),
        ]);
    }
}
