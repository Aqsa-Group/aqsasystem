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
use Illuminate\Pagination\LengthAwarePaginator;

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

    protected $listeners = ['refreshReport' => 'generateReport'];

    public function mount()
    {
        $this->currencies = $this->getCurrencies();
        $this->shopkeepers = Shopkeeper::where('admin_id', $this->getAdminId())
            ->orderBy('fullname')
            ->get(['id', 'fullname'])
            ->toArray();
        $this->markets = Market::where('admin_id', $this->getAdminId())
            ->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();
        $this->expansesTypes = ['پول برق', 'کرایه', 'پول آب', 'صفایی', 'تحت الملکی'];
        $this->generateReport();
    }

    private function getAdminId()
    {
        $user = Auth::guard('market')->user();
        return $user->role === 'admin' ? $user->id : $user->admin_id;
    }

    private function getCurrencies()
    {
        $currencies = Accounting::where('admin_id', $this->getAdminId())
            ->whereNotNull('currency')
            ->distinct()
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

    public function generateReport()
    {
        $adminId = $this->getAdminId();

        $allowedTypes = ['پول برق', 'کرایه', 'پول آب', 'صفایی', 'تحت الملکی'];

        $query = Accounting::where('admin_id', $adminId)
            ->where(function ($q) {
                $q->whereNotNull('shopkeeper_id')
                  ->orWhereNotNull('shop_id')
                  ->orWhereNotNull('booth_id');
            })
            ->whereIn('expanses_type', $allowedTypes);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('shopkeeper', function ($sq) {
                    $sq->where('fullname', 'like', '%' . $this->search . '%');
                })->orWhereHas('shop', function ($sq) {
                    // فقط روی شماره دوکان جستجو شود (ستون name وجود ندارد)
                    $sq->where('number', 'like', '%' . $this->search . '%');
                })->orWhereHas('market', function ($sq) {
                    $sq->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        if (!empty($this->filterShopkeeperId)) {
            $query->where('shopkeeper_id', $this->filterShopkeeperId);
        }
        if (!empty($this->filterMarketId)) {
            $query->where('market_id', $this->filterMarketId);
        }
        if (!empty($this->filterExpansesType)) {
            $query->where('expanses_type', $this->filterExpansesType);
        }
        if (!empty($this->filterCurrency)) {
            $query->where('currency', $this->filterCurrency);
        }

        $subQuery = (clone $query)
            ->selectRaw('MAX(id) as id')
            ->groupBy('shopkeeper_id', 'shop_id', 'market_id', 'expanses_type');

        $latestRecords = Accounting::whereIn('id', $subQuery)
            ->with(['shopkeeper', 'shop', 'market'])
            ->get();

        $summary = [];
        foreach ($latestRecords as $record) {
            $key = $record->shopkeeper_id . '_' . $record->shop_id . '_' . $record->market_id;
            if (!isset($summary[$key])) {
                $summary[$key] = [
                    'shopkeeper_name' => $record->shopkeeper ? $record->shopkeeper->fullname : 'نامشخص',
                    'shop_number' => $record->shop ? $record->shop->number : 'نامشخص',
                    'market_name' => $record->market ? $record->market->name : 'نامشخص',
                    'shopkeeper_id' => $record->shopkeeper_id,
                    'shop_id' => $record->shop_id,
                    'market_id' => $record->market_id,
                    'balances' => [],
                ];
            }
            $summary[$key]['balances'][$record->expanses_type] = (float) $record->remained;
        }

        usort($summary, function ($a, $b) {
            return strcmp($a['shopkeeper_name'], $b['shopkeeper_name']);
        });

        $this->summary = $summary;
    }

    public function getTransactionsPaginated()
    {
        $adminId = $this->getAdminId();

        $startDateCarbon = null;
        $endDateCarbon = null;
        if (!empty($this->startDate)) {
            try {
                $dateString = str_replace('-', '/', $this->startDate);
                $startDateCarbon = Jalalian::fromFormat('Y/m/d', $dateString)->toCarbon()->startOfDay();
            } catch (\Exception $e) {}
        }
        if (!empty($this->endDate)) {
            try {
                $dateString = str_replace('-', '/', $this->endDate);
                $endDateCarbon = Jalalian::fromFormat('Y/m/d', $dateString)->toCarbon()->endOfDay();
            } catch (\Exception $e) {}
        }

        $query = Accounting::with(['shopkeeper', 'shop', 'market'])
            ->where('admin_id', $adminId)
            ->where(function ($q) {
                $q->whereNotNull('shopkeeper_id')
                  ->orWhereNotNull('shop_id')
                  ->orWhereNotNull('booth_id');
            });

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('shopkeeper', function ($sq) {
                    $sq->where('fullname', 'like', '%' . $this->search . '%');
                })->orWhereHas('shop', function ($sq) {
                    $sq->where('number', 'like', '%' . $this->search . '%');
                })->orWhereHas('market', function ($sq) {
                    $sq->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        if (!empty($this->filterShopkeeperId)) {
            $query->where('shopkeeper_id', $this->filterShopkeeperId);
        }
        if (!empty($this->filterMarketId)) {
            $query->where('market_id', $this->filterMarketId);
        }
        if (!empty($this->filterExpansesType)) {
            $query->where('expanses_type', $this->filterExpansesType);
        }
        if (!empty($this->filterCurrency)) {
            $query->where('currency', $this->filterCurrency);
        }
        if ($this->filterTransactionType === 'income') {
            $query->where('paid', '>', 0);
        } elseif ($this->filterTransactionType === 'expense') {
            $query->where('paid', '<', 0);
        }
        if ($startDateCarbon) {
            $query->where('created_at', '>=', $startDateCarbon);
        }
        if ($endDateCarbon) {
            $query->where('created_at', '<=', $endDateCarbon);
        }

        $transactions = $query->orderByDesc('created_at')->paginate(10);

        $items = $transactions->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'shopkeeper_name' => $item->shopkeeper ? $item->shopkeeper->fullname : 'نامشخص',
                'shop_number' => $item->shop ? $item->shop->number : 'نامشخص',
                'market_name' => $item->market ? $item->market->name : 'نامشخص',
                'expanses_type' => $item->expanses_type ?? '-',
                'paid' => (float) $item->paid,
                'currency' => $item->currency,
                'remained' => (float) $item->remained,
                'created_at' => $item->created_at,
                'date_fa' => Jalalian::fromCarbon($item->created_at)->format('Y/m/d H:i'),
                'type' => $item->type ?? '-',
            ];
        });

        $transactions->setCollection($items);
        return $transactions;
    }

    public function getAllTransactions()
    {
        $adminId = $this->getAdminId();

        $startDateCarbon = null;
        $endDateCarbon = null;
        if (!empty($this->startDate)) {
            try {
                $dateString = str_replace('-', '/', $this->startDate);
                $startDateCarbon = Jalalian::fromFormat('Y/m/d', $dateString)->toCarbon()->startOfDay();
            } catch (\Exception $e) {}
        }
        if (!empty($this->endDate)) {
            try {
                $dateString = str_replace('-', '/', $this->endDate);
                $endDateCarbon = Jalalian::fromFormat('Y/m/d', $dateString)->toCarbon()->endOfDay();
            } catch (\Exception $e) {}
        }

        $query = Accounting::with(['shopkeeper', 'shop', 'market'])
            ->where('admin_id', $adminId)
            ->where(function ($q) {
                $q->whereNotNull('shopkeeper_id')
                  ->orWhereNotNull('shop_id')
                  ->orWhereNotNull('booth_id');
            });

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('shopkeeper', function ($sq) {
                    $sq->where('fullname', 'like', '%' . $this->search . '%');
                })->orWhereHas('shop', function ($sq) {
                    $sq->where('number', 'like', '%' . $this->search . '%');
                })->orWhereHas('market', function ($sq) {
                    $sq->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        if (!empty($this->filterShopkeeperId)) {
            $query->where('shopkeeper_id', $this->filterShopkeeperId);
        }
        if (!empty($this->filterMarketId)) {
            $query->where('market_id', $this->filterMarketId);
        }
        if (!empty($this->filterExpansesType)) {
            $query->where('expanses_type', $this->filterExpansesType);
        }
        if (!empty($this->filterCurrency)) {
            $query->where('currency', $this->filterCurrency);
        }
        if ($this->filterTransactionType === 'income') {
            $query->where('paid', '>', 0);
        } elseif ($this->filterTransactionType === 'expense') {
            $query->where('paid', '<', 0);
        }
        if ($startDateCarbon) {
            $query->where('created_at', '>=', $startDateCarbon);
        }
        if ($endDateCarbon) {
            $query->where('created_at', '<=', $endDateCarbon);
        }

        $transactions = $query->orderByDesc('created_at')->get();

        return $transactions->map(function ($item) {
            return [
                'id' => $item->id,
                'shopkeeper_name' => $item->shopkeeper ? $item->shopkeeper->fullname : 'نامشخص',
                'shop_number' => $item->shop ? $item->shop->number : 'نامشخص',
                'market_name' => $item->market ? $item->market->name : 'نامشخص',
                'expanses_type' => $item->expanses_type ?? '-',
                'paid' => (float) $item->paid,
                'currency' => $item->currency,
                'remained' => (float) $item->remained,
                'created_at' => $item->created_at,
                'date_fa' => Jalalian::fromCarbon($item->created_at)->format('Y/m/d H:i'),
                'type' => $item->type ?? '-',
            ];
        })->values();
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

    // ==================== پرینت PDF ====================
    public function printReport()
    {
        try {
            $summary = $this->summary;
            $expansesTypes = $this->expansesTypes;
            $transactions = $this->getAllTransactions();

            $filterInfo = [
                'shopkeeper' => $this->filterShopkeeperId ? Shopkeeper::find($this->filterShopkeeperId)->fullname ?? 'همه' : 'همه',
                'market' => $this->filterMarketId ? Market::find($this->filterMarketId)->name ?? 'همه' : 'همه',
                'expanses_type' => $this->filterExpansesType ?: 'همه',
                'currency' => $this->filterCurrency ? ($this->currencies[$this->filterCurrency] ?? $this->filterCurrency) : 'همه',
                'type' => $this->filterTransactionType === 'all' ? 'همه' : ($this->filterTransactionType === 'income' ? 'دریافتی‌ها' : 'برداشت‌ها'),
                'startDate' => $this->startDate ?: 'نامحدود',
                'endDate' => $this->endDate ?: 'نامحدود',
            ];

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'directionality' => 'rtl',
                'margin_top' => 15,
                'margin_bottom' => 15,
                'margin_left' => 10,
                'margin_right' => 10,
                'fontDir' => array_merge(
                    (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'],
                    [public_path('fonts/vazir/')]
                ),
                'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                    'vazir' => [
                        'R' => 'Vazir-Light.ttf',
                        'B' => 'Vazir-Bold.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ],
                ],
                'default_font' => 'vazir',
                'tempDir' => storage_path('app/mpdf'),
            ]);

            $mpdf->SetAutoPageBreak(true, 15);

            $html = view('print.shopkeeper-profile-pdf', compact(
                'summary',
                'expansesTypes',
                'transactions',
                'filterInfo'
            ))->render();

            $mpdf->WriteHTML($html);

            $fileName = 'گزارش_دوکانداران_' . Jalalian::now()->format('Y_m_d_H_i') . '.pdf';
            $path = storage_path('app/public/' . $fileName);
            $mpdf->Output($path, 'F');

            $this->dispatch('print-pdf', url: asset('storage/' . $fileName));
        } catch (\Exception $e) {
            Log::error('PDF generation error for shopkeeper profile: ' . $e->getMessage());
            session()->flash('error', 'خطا در ایجاد PDF: ' . $e->getMessage());
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