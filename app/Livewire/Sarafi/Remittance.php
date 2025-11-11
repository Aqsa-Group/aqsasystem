<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Remittances;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;

class Remittance extends Component
{
    use WithFileUploads;

    public $confirmDeleteId = null;
    public $remittanceId = null;

    // Form fields
    public $selectedAccount;
    public $toAccount;
    public $source_account;
    public $currency;
    public $amount;
    public $date;
    public $clock;
    public $tracking_code;
    public $from_bank;
    public $to_bank;
    public $zone;
    public $giver_name;
    public $description;
    public $remittance_image;

    // Data collections
    public $currencies = [];
    public $customers = [];
    public $remittances = [];
    public $search = '';
    public $selectedCustomerId = null;
    public $filteredCustomers = [];

    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->clock = now()->format('H:i:s');
        $this->zone = Auth::guard('sarafi')->user()->zone;

        $this->currencies = [
            ['code' => 'usd', 'name_fa' => 'دالر'],
            ['code' => 'afn', 'name_fa' => 'افغانی'],
            ['code' => 'eur', 'name_fa' => 'یورو'],
            ['code' => 'irr', 'name_fa' => 'تومان'],
            ['code' => 'aed', 'name_fa' => 'درهم'],
            ['code' => 'try', 'name_fa' => 'لیره'],
            ['code' => 'cny', 'name_fa' => 'یوان'],
            ['code' => 'pkr', 'name_fa' => 'کلدار'],
            ['code' => 'gbp', 'name_fa' => 'پوند'],
            ['code' => 'jpy', 'name_fa' => 'ین'],
            ['code' => 'sar', 'name_fa' => 'ریال سعودی'],
            ['code' => 'inr', 'name_fa' => 'روپیه'],
        ];

        $this->loadCustomers();
        $this->updateRemittances();
    }

    private function loadCustomers()
    {
        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $this->customers = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

        // Fix the query - remove the remittances relationship check
        $this->customers = Customer::select('id', 'account_number', 'fullname')
            ->where('admin_id', $adminId)
            ->orderBy('fullname')
            ->get();
    }

    public function updatedSearch($value)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        if (empty($value)) {
            $this->selectedCustomerId = null;
            $this->filteredCustomers = [];
            $this->updateRemittances();
            return;
        }

        $this->filteredCustomers = Customer::where('admin_id', $adminId)
            ->where(function($query) use ($value) {
                $query->where('fullname', 'like', "%{$value}%")
                    ->orWhere('account_number', 'like', "%{$value}%");
            })
            ->limit(15)
            ->get();

        if ($this->filteredCustomers->count() === 1) {
            $this->selectCustomer($this->filteredCustomers->first()->id);
        } else {
            $this->selectedCustomerId = null;
            $this->updateRemittances();
        }
    }

    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $this->selectedAccount = $customerId;
        
        $customer = Customer::find($customerId);
        if ($customer) {
            $this->source_account = $customer->account_number;
            $this->search = $customer->fullname;
            $this->updateRemittances();
        }
    }

    public function selectToAccount($customerId)
    {
        $this->toAccount = $customerId;
    }

    public function clearFilter()
    {
        $this->selectedCustomerId = null;
        $this->selectedAccount = null;
        $this->toAccount = null;
        $this->search = '';
        $this->filteredCustomers = [];
        $this->updateRemittances();
    }

    public function clearSearchAndFilter()
    {
        $this->search = '';
        $this->selectedCustomerId = null;
        $this->selectedAccount = null;
        $this->toAccount = null;
        $this->filteredCustomers = [];
        $this->updateRemittances();
    }

    public function updateRemittances()
    {
        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $this->remittances = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;

        $query = Remittances::with(['customer', 'recipient'])
            ->where('admin_id', $adminId);

        if ($this->selectedCustomerId) {
            $query->where('customer_id', $this->selectedCustomerId);
        }

        $this->remittances = $query->latest()->get();
    }

    public function submitRemittance()
    {
        $this->validate([
            'selectedAccount' => 'required|exists:sarafi.customers,id',
            'toAccount' => 'required|exists:sarafi.customers,id',
            'source_account' => 'required|string|max:16',
            'currency' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'clock' => 'required',
            'tracking_code' => 'required|string|max:255',
            'from_bank' => 'required|string|max:255',
            'to_bank' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
            'giver_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'remittance_image' => 'nullable|image|max:10240',
        ]);

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $imagePath = $this->remittance_image ? $this->remittance_image->store('remittances', 'public') : null;

        $data = [
            'customer_id' => $this->selectedAccount,
            'to_account' => $this->toAccount,
            'user_id' => $user->id,
            'admin_id' => $adminId,
            'source_account' => $this->source_account,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'date' => $this->date,
            'clock' => $this->clock,
            'tracking_code' => $this->tracking_code,
            'from_bank' => $this->from_bank,
            'to_bank' => $this->to_bank,
            'zone' => $this->zone,
            'giver_name' => $this->giver_name,
            'description' => $this->description,
            'remittance_image' => $imagePath,
        ];

        if ($this->remittanceId) {
            $remittance = Remittances::findOrFail($this->remittanceId);
            
            // Delete old image if new one is uploaded
            if ($imagePath && $remittance->remittance_image) {
                Storage::disk('public')->delete($remittance->remittance_image);
            }
            
            $remittance->update($data);
            session()->flash('message', 'حواله با موفقیت بروزرسانی شد.');
        } else {
            Remittances::create($data);
            session()->flash('message', 'حواله با موفقیت ثبت شد.');
        }

        $this->updateRemittances();
        $this->resetForm();
    }

    public function edit($id)
    {
        $remittance = Remittance::with(['customer', 'recipient'])->findOrFail($id);
        
        $this->remittanceId = $id;
        $this->selectedAccount = $remittance->customer_id;
        $this->toAccount = $remittance->to_account;
        $this->source_account = $remittance->source_account;
        $this->currency = $remittance->currency;
        $this->amount = $remittance->amount;
        $this->date = $remittance->date;
        $this->clock = $remittance->clock;
        $this->tracking_code = $remittance->tracking_code;
        $this->from_bank = $remittance->from_bank;
        $this->to_bank = $remittance->to_bank;
        $this->zone = $remittance->zone;
        $this->giver_name = $remittance->giver_name;
        $this->description = $remittance->description;
        
        // Set the search values for display
        $this->search = $remittance->customer->fullname ?? '';
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        $remittance = Remittance::findOrFail($this->confirmDeleteId);
        
        // Delete associated image
        if ($remittance->remittance_image) {
          Storage::disk('public')->delete($remittance->remittance_image);
        }
        
        $remittance->delete();

        session()->flash('message', 'حواله با موفقیت حذف شد.');
        $this->updateRemittances();
        $this->confirmDeleteId = null;
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'remittanceId',
            'selectedAccount',
            'toAccount',
            'source_account',
            'currency',
            'amount',
            'clock',
            'tracking_code',
            'from_bank',
            'to_bank',
            'giver_name',
            'description',
            'remittance_image',
        ]);

        $this->date = Jalalian::now()->format('Y/m/d');
        $this->clock = now()->format('H:i:s');
        $this->zone = Auth::guard('sarafi')->user()->zone;
        $this->search = '';
    }

    public function render()
    {
        $user = Auth::guard('sarafi')->user();

        if (!$user) {
            return view('livewire.sarafi.remittance', [
                'customers' => collect(),
                'remittances' => collect(),
            ]);
        }

        // Reload customers if empty
        if (!$this->customers || $this->customers->isEmpty()) {
            $this->loadCustomers();
        }

        return view('livewire.sarafi.remittance', [
            'customers' => $this->customers,
            'remittances' => $this->remittances,
        ]);
    }
}