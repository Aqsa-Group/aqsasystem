<?php

namespace App\Livewire\Import;

use App\Models\Import\Customer;
use App\Models\Import\CustomerBalance;
use App\Models\Import\CustomerStory;
use App\Models\Import\CustomerLoan as Loan;
use App\Models\Import\Safe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;

class CustomerLoan extends Component
{
    public $search = '';
    public $confirmDeleteId = null;

    // فرم فیلدها
    public $customer_id, $type, $amount, $currency, $date, $description;
    public $isEdit = false;
    public $editId = null;
    public $showModal = false;

    public $customers = [];

    public $types = ['رسید' => 'رسید', 'برد' => 'برد'];
    public $currencies = [
        'AFN' => 'افغانی',
        'USD' => 'دلار', 
        'EUR' => 'یورو',
        'PKR' => 'کلدار'
    ];

    private function getUser()
    {
        return Auth::guard('import')->user();
    }

    public function mount()
    {
        $this->loadCustomers();
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->currency = 'AFN';
    }

    public function loadCustomers()
    {
        $queryCustomers = Customer::query();
        $this->customers = $queryCustomers->get(['id', 'name'])->toArray();
    }

    protected function rules()
    {
        return [
            'customer_id' =>  'required|exists:import.customers,id',
            'type'        => 'required|in:رسید,برد',
            'amount'      => 'required|numeric|min:0.01',
            'currency'    => 'required|in:AFN,USD,EUR,PKR',
            'date' => ['required', function ($attribute, $value, $fail) {
                $value = str_replace('/', '-', $value);

                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                    $fail('فرمت تاریخ صحیح نیست (YYYY-MM-DD)');
                    return;
                }

                try {
                    Jalalian::fromFormat('Y-m-d', $value);
                } catch (\Exception $e) {
                    $fail('تاریخ وارد شده نامعتبر است.');
                }
            }],
            'description' => 'nullable|string',
        ];
    }

    public function resetForm()
    {
        $this->reset(['customer_id', 'type', 'amount', 'currency', 'date', 'description', 'isEdit', 'editId']);
        $this->resetValidation();
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->currency = 'AFN';
    }

    public function print($loanId)
    {
        $loan = Loan::with('customer')->findOrFail($loanId);
        $user = $this->getUser();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [72.1, 297],
            'directionality' => 'rtl',
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
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

        $mpdf->SetAutoPageBreak(false);

        $mpdf->WriteHTML(view('pdf.import.customer-loan', [
            'loan' => $loan,
            'customer' => $loan->customer,
            'copyType' => $loan->type === 'رسید' ? 'رسید پرداخت' : 'رسید دریافت',
        ])->render());

        $fileName = 'customer_loan_' . $loan->id . '_' . time() . '.pdf';
        $path = storage_path('app/public/' . $fileName);

        if (file_exists($path)) unlink($path);
        $mpdf->Output($path, 'F');

        if (!file_exists($path)) {
            throw new \Exception('خطا در ایجاد فایل PDF');
        }

        $this->dispatch('print-pdf', url: asset('storage/' . $fileName) . '?t=' . time());
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        // اصلاح: استفاده از Loan به جای CustomerLoan
        $loan = Loan::findOrFail($id);
        
        $this->resetForm();
        $this->isEdit = true;
        $this->editId = $loan->id;
        $this->customer_id = $loan->customer_id;
        $this->type = $loan->type;
        $this->amount = $loan->amount;
        $this->currency = $loan->currency;
        $this->date = $loan->date;
        $this->description = $loan->description;
        $this->showModal = true;
    }

    // عملیات معکوس (برای ویرایش و حذف)
    private function reverseLoanOperation($loan)
    {
        $isReceipt = ($loan->type === 'رسید');
        $amount = $loan->amount;
        $currency = $loan->currency;

        // برگرداندن موجودی مشتری
        $balance = CustomerBalance::where('customer_id', $loan->customer_id)->first();
        if ($balance) {
            if ($isReceipt) {
                $balance->{$currency} -= $amount;
            } else {
                $balance->{$currency} += $amount;
            }
            $balance->save();
        }

        // برگرداندن صندوق
        $safe = Safe::first();
        if ($safe) {
            if ($isReceipt) {
                $safe->{$currency} -= $amount;
            } else {
                $safe->{$currency} += $amount;
            }
            $safe->save();
        }
    }

    public function save()
    {
        $this->validate();

        $user = $this->getUser();
        $adminId = $user->admin_id ?? $user->id;
        $isReceipt = ($this->type === 'رسید');

        DB::connection('import')->beginTransaction();
        try {
            if ($this->isEdit) {
                // اصلاح: استفاده از Loan به جای CustomerLoan
                $oldLoan = Loan::findOrFail($this->editId);
                $this->reverseLoanOperation($oldLoan);
                CustomerStory::where('CustomerLoan_id', $oldLoan->id)->delete();
                $oldLoan->delete();
            }

            // ایجاد قرض جدید - استفاده از Loan
            $loan = Loan::create([
                'customer_id' => $this->customer_id,
                'type' => $this->type,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'date' => $this->date,
                'description' => $this->description,
                'user_id' => $user->id,
                'admin_id' => $adminId,
            ]);

            // به‌روزرسانی موجودی مشتری
            $balance = CustomerBalance::firstOrCreate(
                ['customer_id' => $this->customer_id],
                [
                    'AFN' => 0, 
                    'USD' => 0, 
                    'PKR' => 0, 
                    'EUR' => 0,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                ]
            );
            
            $current = $balance->{$this->currency} ?? 0;
            $newBalance = $isReceipt ? $current + $this->amount : $current - $this->amount;
            $balance->{$this->currency} = $newBalance;
            $balance->save();

            // ثبت در تاریخچه مشتری
            CustomerStory::create([
                'customer_id' => $this->customer_id,
                'type' => $this->type,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'date' => $this->date,
                'description' => $this->description,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'CustomerLoan_id' => $loan->id,
            ]);

            // به‌روزرسانی صندوق
            $safe = Safe::first();
            if (!$safe) {
                $safe = Safe::create([
                    'AFN' => 0,
                    'USD' => 0,
                    'currency' => 1,
                    'user_id' => $user->id,
                    'today' => now()->format('Y-m-d'),
                ]);
            }
            
            $currentSafe = $safe->{$this->currency} ?? 0;
            if (!$isReceipt && $currentSafe < $this->amount) {
                throw new \Exception("موجودی صندوق در ارز " . $this->currencies[$this->currency] . " کافی نیست.");
            }
            $newSafe = $isReceipt ? $currentSafe + $this->amount : $currentSafe - $this->amount;
            $safe->{$this->currency} = $newSafe;
            $safe->save();

            DB::connection('import')->commit();
            session()->flash('message', $this->isEdit ? 'قرض با موفقیت ویرایش شد.' : 'قرض جدید با موفقیت ثبت شد.');
            $this->resetForm();
            $this->showModal = false;
        } catch (\Exception $e) {
            DB::connection('import')->rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        // اصلاح: استفاده از Loan به جای CustomerLoan
        $loan = Loan::findOrFail($this->confirmDeleteId);

        DB::connection('import')->beginTransaction();
        try {
            $this->reverseLoanOperation($loan);
            CustomerStory::where('CustomerLoan_id', $loan->id)->delete();
            $loan->delete();
            DB::connection('import')->commit();
            session()->flash('message', 'قرض با موفقیت حذف شد.');
        } catch (\Exception $e) {
            DB::connection('import')->rollBack();
            session()->flash('error', 'خطا در حذف قرض: ' . $e->getMessage());
        }
        $this->confirmDeleteId = null;
    }

    public function render()
    {
        // اصلاح: استفاده از Loan به جای CustomerLoan
        $query = Loan::with('customer');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('amount', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        $loans = $query->latest()->paginate(10);
        $this->loadCustomers();

        return view('livewire.import.customer-loan', [
            'loans' => $loans,
            'customers' => $this->customers,
        ]);
    }
}