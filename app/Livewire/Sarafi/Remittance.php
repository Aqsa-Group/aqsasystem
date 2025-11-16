<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\RemittanceApproval;
use App\Models\Sarafi\Remittances;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;

class Remittance extends Component
{
    use WithFileUploads;

    public $confirmDeleteId = null;
    public $remittanceId = null;
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
    public $source_account_last_four;

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
            ->where(function ($query) use ($value) {
                $query->where('fullname', 'like', "%{$value}%")
                    ->orWhere('account_number', 'like', "%{$value}%");
            })
            ->limit(15)
            ->get();

        if (count($this->filteredCustomers) === 1) {
            $this->selectCustomer($this->filteredCustomers[0]['id']);
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
    $this->amount = str_replace(',', '', $this->amount);
    $this->source_account = $this->source_account_last_four . ' - xxxx - xxxx - xxxx';

    $this->validate([
        'selectedAccount' => 'required|exists:sarafi.customers,id',
        'toAccount' => 'required|exists:sarafi.customers,id',
        'source_account' => 'required|string|max:255',
        'source_account_last_four' => 'required|digits:4',
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
        'state' => 0, 
    ];

    if ($this->remittanceId) {
        $remittance = Remittances::findOrFail($this->remittanceId);

        if ($imagePath && $remittance->remittance_image) {
            Storage::disk('public')->delete($remittance->remittance_image);
        }

        $remittance->update($data);
        $this->updateRemittanceApproval($remittance);
        session()->flash('message', 'حواله با موفقیت بروزرسانی شد.');
    } else {
        $remittance = Remittances::create($data);
        
        $this->createRemittanceApproval($remittance);
        
        session()->flash('message', 'حواله با موفقیت ثبت شد و برای تایید ارسال گردید.');
    }

    $this->updateRemittances();
    $this->resetForm();
}


 
private function createRemittanceApproval($remittance)
{
    RemittanceApproval::create([
        'remittance_id' => $remittance->id,
        'customer_id' => $remittance->customer_id,
        'to_account' => $remittance->to_account,
        'user_id' => $remittance->user_id,
        'admin_id' => $remittance->admin_id,
        'source_account' => $remittance->source_account,
        'currency' => $remittance->currency,
        'amount' => $remittance->amount,
        'date' => $remittance->date,
        'clock' => $remittance->clock,
        'tracking_code' => $remittance->tracking_code,
        'from_bank' => $remittance->from_bank,
        'to_bank' => $remittance->to_bank,
        'zone' => $remittance->zone,
        'giver_name' => $remittance->giver_name,
        'description' => $remittance->description,
        'remittance_image' => $remittance->remittance_image,
        'approved' => 0,
    ]);
}


  public function edit($id)
{
    $remittance = Remittances::with(['customer', 'recipient'])->findOrFail($id);

    $this->remittanceId = $id;
    $this->selectedAccount = $remittance->customer_id;
    $this->toAccount = $remittance->to_account;
    $this->source_account = $remittance->source_account;

    $this->source_account_last_four = substr($remittance->source_account, 0, 4);

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

    $this->search = $remittance->customer->fullname ?? '';

    $this->updateRemittanceApproval($remittance);
}

private function updateRemittanceApproval(Remittances $remittance)
{
    $approval = RemittanceApproval::where('remittance_id', $remittance->id)->first();
    
    if ($approval) {
        $approval->update([
            'customer_id' => $remittance->customer_id,
            'to_account' => $remittance->to_account,
            'source_account' => $remittance->source_account,
            'currency' => $remittance->currency,
            'amount' => $remittance->amount,
            'date' => $remittance->date,
            'clock' => $remittance->clock,
            'tracking_code' => $remittance->tracking_code,
            'from_bank' => $remittance->from_bank,
            'to_bank' => $remittance->to_bank,
            'zone' => $remittance->zone,
            'giver_name' => $remittance->giver_name,
            'description' => $remittance->description,
            'remittance_image' => $remittance->remittance_image,
         
        ]);
    }
}
    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

   public function deleteConfirmed()
    {
        DB::connection('sarafi')->transaction(function () {
            $remittance = Remittances::findOrFail($this->confirmDeleteId);
            
            // اگر حواله تایید شده است، عملیات برگشت انجام شود
            if ($remittance->state == 1) {
                $this->reverseApprovedRemittance($remittance);
            } else {
                // اگر حواله تایید نشده، فقط حذف ساده
                $this->deletePendingRemittance($remittance);
            }

            session()->flash('message', 'حواله با موفقیت حذف شد.');
        });

        $this->updateRemittances();
        $this->confirmDeleteId = null;
    }



    /**
     * برگشت دادن حواله تایید شده
     */
      private function reverseApprovedRemittance(Remittances $remittance)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // 1. برگشت تراکنش‌های ایجاد شده
        $this->reverseTransactions($remittance, $user, $adminId);

        // 2. برگشت موجودی بانک
        $this->reverseBankAccount($remittance, $user, $adminId);

        // 3. آپدیت وضعیت تایید به "حذف شده"
        $approval = RemittanceApproval::where('remittance_id', $remittance->id)->first();
        if ($approval) {
            $approval->update([
                'approved' => 3, // 3 = حذف شده
                'deleted_by' => $user->id,
                'deleted_at' => now(),
            ]);
        }

        // 4. حذف تصویر اگر وجود دارد
        if ($remittance->remittance_image) {
            Storage::disk('public')->delete($remittance->remittance_image);
        }

        // 5. حذف حواله اصلی
        $remittance->delete();
    }




        /**
     * حذف حواله در انتظار تایید
     */
      private function deletePendingRemittance(Remittances $remittance)
    {
        // حذف درخواست تایید
        RemittanceApproval::where('remittance_id', $remittance->id)->delete();

        // حذف تصویر اگر وجود دارد
        if ($remittance->remittance_image) {
            Storage::disk('public')->delete($remittance->remittance_image);
        }

        // حذف حواله اصلی
        $remittance->delete();
    }

 private function reverseTransactions(Remittances $remittance, $user, $adminId)
    {
        // پیدا کردن تراکنش‌های مربوط به این حواله
        $transactions = Transaction::where('remittance_id', $remittance->id)
            ->where(function ($q) use ($adminId, $user) {
                $q->where('admin_id', $adminId)
                  ->orWhere('user_id', $user->id);
            })
            ->get();

        foreach ($transactions as $transaction) {
            // ایجاد تراکنش معکوس برای مشتری فرستنده (برداشت برگشتی)
            if ($transaction->customer_id) {
                Transaction::create([
                    'customer_id' => $transaction->customer_id,
                    'remittance_id' => $transaction->remittance_id,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'date' => now(),
                    'type' => 'برد', // معکوس کردن نوع - برداشت از مشتری
                    'amount' => $transaction->amount,
                    'currency' => $transaction->currency,
                    'account_type' => 'بانکی',
                    'description' => 'برگشت حواله حذف شده - ' . ($transaction->description ?? ''),
                    'document_number' => 'REV-' . ($transaction->document_number ?? 'REM-' . $remittance->id),
                    'zone' => $transaction->zone,
                    'by' => $user->name,
                    'rate' => $transaction->rate ?? 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ایجاد تراکنش معکوس برای مشتری گیرنده (واریز برگشتی)
            if ($transaction->recipient_id) {
                Transaction::create([
                    'recipient_id' => $transaction->recipient_id,
                    'remittance_id' => $transaction->remittance_id,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'date' => now(),
                    'type' => 'رسید', // معکوس کردن نوع - واریز به گیرنده
                    'amount' => $transaction->amount,
                    'currency' => $transaction->currency,
                    'account_type' => 'بانکی',
                    'description' => 'برگشت حواله حذف شده - ' . ($transaction->description ?? ''),
                    'document_number' => 'REV-' . ($transaction->document_number ?? 'REM-' . $remittance->id),
                    'zone' => $transaction->zone,
                    'by' => $user->name,
                    'rate' => $transaction->rate ?? 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // غیرفعال کردن تراکنش اصلی (اختیاری)
            $transaction->update(['status' => 'reversed']);
        }
    }

       /**
     * برگشت موجودی بانک
     */
   private function reverseBankAccount(Remittances $remittance, $user, $adminId)
    {
        $bankAccount = BankAccount::where('admin_id', $adminId)->first();

        if ($bankAccount) {
            $currentBalance = $bankAccount->{$remittance->currency} ?? 0;
            
            // کاهش موجودی بانک (چون هنگام تایید افزایش یافته بود)
            if ($currentBalance >= $remittance->amount) {
                $bankAccount->decrement($remittance->currency, $remittance->amount);
            } else {
                // اگر موجودی کافی نبود، فقط تا صفر کاهش دهید
                $bankAccount->update([
                    $remittance->currency => 0
                ]);
                
                Log::warning("موجودی بانک برای برگشت کامل حواله کافی نبود", [
                    'remittance_id' => $remittance->id,
                    'currency' => $remittance->currency,
                    'amount' => $remittance->amount,
                    'current_balance' => $currentBalance
                ]);
            }
        }
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
            'source_account_last_four',
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
    // Add this method for amount formatting
    public function formatAmount()
    {
        if ($this->amount) {
            $this->amount = number_format((float) $this->amount);
        }
    }

    // Add this method for setting default zone
    public function setDefaultZone()
    {
        $this->zone = Auth::guard('sarafi')->user()->zone;
    }

    // Add this method for submit and print
    public function submitAndPrint()
    {
        $this->submitRemittance();
        // Add print logic here
    }

    // Add this method for printing
    public function print($id)
    {
        // Add print logic here
        $this->dispatch('report-alert', message: 'Print functionality for remittance ID: ' . $id);
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
        if (collect($this->customers)->isEmpty()) {
            $this->loadCustomers();
        }

        return view('livewire.sarafi.remittance', [
            'customers' => $this->customers,
            'remittances' => $this->remittances,
        ]);
    }
}
