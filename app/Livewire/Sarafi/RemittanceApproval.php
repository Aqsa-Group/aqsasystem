<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\RemittanceApproval as ApprovalModel;
use App\Models\Sarafi\Remittances;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RemittanceApproval extends Component
{
    public $confirmApproveId = null;
    public $confirmRejectId = null;
    public $approvalNotes = '';

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
            ]);

            // آپدیت وضعیت حواله اصلی
            $remittance = Remittances::find($approval->remittance_id);
            if ($remittance) {
                $remittance->update(['state' => 1]);
            }

           

            // آپدیت موجودی بانک
            $this->updateBankAccount($approval);

            session()->flash('message', 'حواله با موفقیت تایید شد و مبلغ به حساب مقصد واریز گردید.');
        });

        $this->confirmApproveId = null;
        $this->approvalNotes = '';
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

    // متد جایگزین اگر می‌خواهید از increment استفاده کنید
    private function updateBankAccountAlternative(ApprovalModel $approval)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // پیدا کردن یا ایجاد حساب بانکی
        $bankAccount = BankAccount::firstOrCreate(
            ['admin_id' => $adminId],
            [
                'user_id' => $user->id,
                'admin_id' => $adminId,
                // مقادیر پیش‌فرض برای سایر ارزها
                'usd' => 0,
                'afn' => 0,
                'eur' => 0,
                'irr' => 0,
                'aed' => 0,
                'try' => 0,
                'cny' => 0,
                'pkr' => 0,
                'gbp' => 0,
                'jpy' => 0,
                'sar' => 0,
                'inr' => 0,
            ]
        );

        // افزایش موجودی با استفاده از increment
        $bankAccount->increment($approval->currency, $approval->amount);
    }

    // متد برای کاهش موجودی بانک (اگر نیاز باشد)
    private function decreaseBankAccount(ApprovalModel $approval)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $bankAccount = BankAccount::where('admin_id', $adminId)->first();

        if ($bankAccount) {
            $currentBalance = $bankAccount->{$approval->currency} ?? 0;
            $newBalance = $currentBalance - $approval->amount;

            // مطمئن شوید موجودی منفی نشود
            if ($newBalance >= 0) {
                $bankAccount->update([
                    $approval->currency => $newBalance
                ]);
            } else {
                throw new \Exception('موجودی کافی نیست');
            }
        }
    }

    public function cancelAction()
    {
        $this->confirmApproveId = null;
        $this->confirmRejectId = null;
        $this->approvalNotes = '';
    }

    public function render()
    {
        $pendingApprovals = $this->getPendingApprovals();
        
        return view('livewire.sarafi.remittance-approval', [
            'pendingApprovals' => $pendingApprovals
        ]);
    }
}