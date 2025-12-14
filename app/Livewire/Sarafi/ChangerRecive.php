<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\ChangerDeal;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ChangerRecive extends Component
{
    use WithPagination;

    // فیلترها
    public $fromCustomer = null;
    public $toCustomer = null;
    public $selectedCurrency = null;
    public $selectedZone = null;
    public $selectedSenderSarafi = null;

    // لیست‌ها
    public $customers = [];
    public $sarafis = [];
    public $zones = [];

    // کاربر
    public $user;
    public $effectiveAdminId;

    protected $currencies = [
        'usd' => 'دالر',
        'afn' => 'افغانی',
        'irr' => 'تومان',
        'eur' => 'یورو',
        'pkr' => 'کلدار',
        'aed' => 'درهم',
        'try' => 'لیره',
        'cny' => 'یوان',
    ];

    public function mount()
    {
        $this->user = Auth::guard('sarafi')->user();

        // تعیین admin موثر
        $this->effectiveAdminId = ($this->user->role === 'admin' && !$this->user->admin_id)
            ? $this->user->id
            : ($this->user->admin_id ?? $this->user->id);

        $this->loadZones();
        $this->loadCustomers();
        $this->loadSarafis();
    }

    private function loadZones()
    {
        $this->zones = ChangerDeal::where('to_sarafi', $this->effectiveAdminId)
            ->distinct()
            ->pluck('zone')
            ->filter()
            ->values()
            ->toArray();
    }

    private function loadCustomers()
    {
        $customerIds = ChangerDeal::where('to_sarafi', $this->effectiveAdminId)
            ->pluck('from_customer')
            ->merge(
                ChangerDeal::where('to_sarafi', $this->effectiveAdminId)
                    ->pluck('to_customer')
            )
            ->unique()
            ->filter()
            ->values()
            ->toArray();

        $this->customers = Customer::whereIn('id', $customerIds)
            ->orderBy('fullname')
            ->get();
    }
private function loadSarafis()
{
    $sarafiIds = ChangerDeal::where('to_sarafi', $this->effectiveAdminId)
        ->pluck('from_sarafi')
        ->unique()
        ->filter();

    $this->sarafis = User::whereIn('id', $sarafiIds)
        ->orderBy('sarafi_name')
        ->get();
}



    public function render()
    {
        $query = ChangerDeal::query()
            ->with([
                'fromCustomer',
                'toCustomer',
                'fromSarafiUser',
            ])
            ->where('to_sarafi', $this->effectiveAdminId); // شرط اصلی

        // اعمال فیلترها
        if ($this->fromCustomer) {
            $query->where('from_customer', $this->fromCustomer);
        }

        if ($this->toCustomer) {
            $query->where('to_customer', $this->toCustomer);
        }

        if ($this->selectedCurrency) {
            $query->where('currency', $this->selectedCurrency);
        }

        if ($this->selectedZone) {
            $query->where('zone', $this->selectedZone);
        }

        if ($this->selectedSenderSarafi) {
            $query->where('from_sarafi', $this->selectedSenderSarafi);
        }

        $deals = $query->orderByDesc('id')->paginate(20);

        return view('livewire.sarafi.changer-recive', [
            'deals' => $deals,
            'currencies' => $this->currencies,
        ]);
    }

    public function resetFilters()
    {
        $this->reset([
            'fromCustomer',
            'toCustomer',
            'selectedCurrency',
            'selectedZone',
            'selectedSenderSarafi',
        ]);
        
        $this->resetPage();
    }

    public function printReport()
    {
        // منطق چاپ گزارش
        $this->dispatch('print-report');
    }

    public function refreshReport()
    {
        // بارگذاری مجدد داده‌ها
        $this->loadZones();
        $this->loadCustomers();
        $this->loadSarafis();
    }
}