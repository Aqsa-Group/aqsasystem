<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\RemittanceApproval as ApprovalModel;
use App\Models\Sarafi\Remittances;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\User;
use App\Models\Sarafi\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RemittanceApproval extends Component
{
    public $confirmApproveId = null;
    public $confirmRejectId = null;
    public $approvalNotes = '';
    
    // اضافه کردن پراپرتی‌های کمیشن
    public $withCommission = false;
    public $commissionAccount = null;
    public $commissionCurrency = '';
    public $commissionAmount = 0;

    // اضافه کردن لیست مشتریان
    public $customers = [];

    public function mount()
    {
        $this->loadCustomers();
    }

    private function loadCustomers()
    {
        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $this->customers = [];
            return;
        }

        $adminId = $user->admin_id ?? $user->id;

        $this->customers = Customer::where('admin_id', $adminId)
            ->orderBy('fullname')
            ->get(['id', 'account_number', 'fullname'])
            ->toArray();
    }

    public function getPendingApprovals()
    {
        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            return collect();
        }

        $adminId = $user->admin_id ?? $user->id;

        return ApprovalModel::with(['customer', 'recipient', 'user'])
            ->where('admin_id', $adminId)
            ->pending()
            ->latest()
            ->get();
    }

    public function confirmApprove($approvalId)
    {
        $this->confirmApproveId = $approvalId;
        // بارگذاری اطلاعات حواله برای محاسبه کمیشن
        $approval = ApprovalModel::find($approvalId);
        if ($approval) {
            // محاسبه خودکار کمیشن (مثلاً 1% از مبلغ حواله)
            $this->commissionAmount = $approval->amount * 0.01;
            $this->commissionCurrency = $approval->currency;
        }
    }

    public function confirmReject($approvalId)
    {
        $this->confirmRejectId = $approvalId;
    }

    public function approveRemittance()
    {
        DB::connection('sarafi')->transaction(function () {
            $user = Auth::guard('sarafi')->user();
            $approval = ApprovalModel::findOrFail($this->confirmApproveId);
            
            // آپدیت وضعیت تایید
            $approval->update([
                'approved' => 1,
                'approved_by' => $user->id,
                'approved_at' => now(),
                'approval_notes' => $this->approvalNotes,
                'with_commission' => $this->withCommission,
                'commission_account' => $this->commissionAccount,
                'commission_currency' => $this->commissionCurrency,
                'commission_amount' => $this->commissionAmount,
            ]);

            // آپدیت وضعیت حواله اصلی
            $remittance = Remittances::find($approval->remittance_id);
            if ($remittance) {
                $remittance->update([
                    'state' => 1,
                    'with_commission' => $this->withCommission,
                    'commission_account' => $this->commissionAccount,
                    'commission_currency' => $this->commissionCurrency,
                    'commission_amount' => $this->commissionAmount,
                ]);
            }

            // ایجاد تراکنش‌های مالی
            $this->createTransactions($approval, $user);

            // اگر کمیشن فعال است، تراکنش کمیشن ایجاد کن
            if ($this->withCommission && $this->commissionAccount && $this->commissionAmount > 0) {
                $this->createCommissionTransaction($approval, $user);
            }

            // آپدیت موجودی بانک
            $this->updateBankAccount($approval);

            $message = 'حواله با موفقیت تایید شد';
            if ($this->withCommission) {
                $message .= ' و کمیشن ' . number_format($this->commissionAmount) . ' ' . $this->getCurrencyName($this->commissionCurrency) . ' ثبت شد';
            }
            
            session()->flash('message', $message);
        });

        $this->resetApprovalForm();
    }

    /**
     * ایجاد تراکنش‌های مالی هنگام تایید حواله
     */
    private function createTransactions(ApprovalModel $approval, $user)
    {
        $adminId = $user->admin_id ?? $user->id;

   
        Transaction::create([
            'customer_id' => $approval->customer_id,
            'remittance_id' => $approval->remittance_id,
            'user_id' => $user->id,
            'admin_id' => $adminId,
            'date' => now(),
            'type' => 'رسید',
            'amount' => $approval->amount,
            'currency' => $approval->currency,
            'account_type' => 'بانکی',
            'description' => 'برداشت برای حواله - شماره پیگیری: ' . $approval->tracking_code,
            'document_number' => 'REM-' . $approval->remittance_id . '-OUT',
            'zone' => $approval->zone,
            'by' => $user->name,
            'rate' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Transaction::create([
            'customer_id' => $approval->to_account,
            'remittance_id' => $approval->remittance_id,
            'user_id' => $user->id,
            'admin_id' => $adminId,
            'date' => now(),
            'type' => 'رسید',
            'amount' => $approval->amount,
            'currency' => $approval->currency,
            'account_type' => 'بانکی',
            'description' => 'دریافت حواله - شماره پیگیری: ' . $approval->tracking_code,
            'document_number' => 'REM-' . $approval->remittance_id . '-IN',
            'zone' => $approval->zone,
            'by' => $user->name,
            'rate' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * ایجاد تراکنش کمیشن
     */
    private function createCommissionTransaction(ApprovalModel $approval, $user)
    {
        $adminId = $user->admin_id ?? $user->id;

       
        Transaction::create([
            'customer_id' => $approval->customer_id,
            'remittance_id' => $approval->remittance_id,
            'user_id' => $user->id,
            'admin_id' => $adminId,
            'date' => now(),
            'type' => 'برد',
            'amount' => $this->commissionAmount,
            'currency' => $this->commissionCurrency,
            'account_type' => 'بانکی',
            'description' => 'کمیشن حواله - شماره پیگیری: ' . $approval->tracking_code,
            'document_number' => 'COMM-' . $approval->remittance_id,
            'zone' => $approval->zone,
            'by' => $user->name,
            'rate' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

            Transaction::create([
            'customer_id' => $this->commissionAccount,
            'remittance_id' => $approval->remittance_id,
            'user_id' => $user->id,
            'admin_id' => $adminId,
            'date' => now(),
            'type' => 'رسید',
            'amount' => $this->commissionAmount,
            'currency' => $this->commissionCurrency,
            'account_type' => 'بانکی',
            'description' => 'دریافت کمیشن حواله - شماره پیگیری: ' . $approval->tracking_code,
            'document_number' => 'COMM-' . $approval->remittance_id,
            'zone' => $approval->zone,
            'by' => $user->name,
            'rate' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function rejectRemittance()
    {
        $user = Auth::guard('sarafi')->user();
        $approval = ApprovalModel::findOrFail($this->confirmRejectId);
        
        $approval->update([
            'approved' => 2,
            'approved_by' => $user->id,
            'approved_at' => now(),
            'approval_notes' => $this->approvalNotes,
        ]);

        session()->flash('message', 'حواله رد شد.');
        
        $this->confirmRejectId = null;
        $this->approvalNotes = '';
    }

    private function updateBankAccount(ApprovalModel $approval)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $bankAccount = BankAccount::where('admin_id', $adminId)->first();

        if (!$bankAccount) {
            $bankAccount = BankAccount::create([
                'user_id' => $user->id,
                'admin_id' => $adminId,
                $approval->currency => $approval->amount,
            ]);
        } else {
            $currentBalance = $bankAccount->{$approval->currency} ?? 0;
            $newBalance = $currentBalance + $approval->amount;

            $bankAccount->update([
                $approval->currency => $newBalance
            ]);
        }
    }

    // متد برای ریست فرم تایید
    private function resetApprovalForm()
    {
        $this->confirmApproveId = null;
        $this->confirmRejectId = null;
        $this->approvalNotes = '';
        $this->withCommission = false;
        $this->commissionAccount = null;
        $this->commissionCurrency = '';
        $this->commissionAmount = 0;
    }

    // متد برای گرفتن نام ارز
    private function getCurrencyName($currencyCode)
    {
        $currencies = [
            'usd' => 'دالر',
            'afn' => 'افغانی',
            'eur' => 'یورو',
            'irr' => 'تومان',
            'aed' => 'درهم',
            'try' => 'لیره',
            'cny' => 'یوان',
            'pkr' => 'کلدار',
        ];

        return $currencies[$currencyCode] ?? $currencyCode;
    }

    public function cancelAction()
    {
        $this->resetApprovalForm();
    }

    public function render()
    {
        $pendingApprovals = $this->getPendingApprovals();
        
        return view('livewire.sarafi.remittance-approval', [
            'pendingApprovals' => $pendingApprovals,
            'customers' => $this->customers
        ]);
    }
}