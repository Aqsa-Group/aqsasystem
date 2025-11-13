<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Trash;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TrashEdit extends Component
{
    use WithPagination;

    public $filterOpen = false;
    public $filterAction = '';
    public $filterDocumentType = '';
    public $search = '';

    public $showDetailsModal = false;
    public $selectedRecord = null;
    public $confirmDeleteId = null;


    protected $queryString = [
        'search' => ['except' => ''],
        'filterAction' => ['except' => ''],
        'filterDocumentType' => ['except' => ''],
    ];

    public function mount()
    {
        //
    }

    public function applyFilter()
    {
        $this->resetPage();
        $this->filterOpen = false;
    }

    public function showDetails($id)
    {
        $this->selectedRecord = Trash::with(['user', 'admin', 'registeredUser'])->find($id);
        $this->showDetailsModal = true;
    }

    public function closeDetails()
    {
        $this->showDetailsModal = false;
        $this->selectedRecord = null;
    }

    public function getDocumentTypeLabel($type)
    {
        $labels = [
            'transactions' => 'تراکنش‌ها',
            'transferinaccount' => 'انتقال به حساب',
            'conversion_transfer' => 'تبدیل ارز',
            'account_to_account' => 'حساب به حساب',
            'cash_exchange' => 'صرافی نقدی',
            'remittance' => 'حواله',
            'رسید /برد صندوق' => 'رسید / برد صندوق',
            'ثبت احواله ها' => 'ثبت حواله‌ها',
        ];

        return $labels[$type] ?? $type;
    }

    public function getFieldLabel($field)
    {
        $labels = [
            'id' => 'شناسه',
            'customer_id' => 'شناسه مشتری',
            'user_id' => 'شناسه کاربر',
            'admin_id' => 'شناسه ادمین',
            'conversion_transfer_id' => 'شناسه تبدیل حواله',
            'conversion_in_account_id' => 'شناسه تبدیل به حساب',
            'account_to_id' => 'شناسه حساب مقصد',
            'currency' => 'ارز',
            'zone' => 'منطقه',
            'by' => 'توسط',
            'amount' => 'مبلغ',
            'type' => 'نوع',
            'account_type' => 'نوع حساب',
            'date' => 'تاریخ',
            'description' => 'شرح',
            'transaction_file' => 'فایل تراکنش',
            'created_at' => 'تاریخ ایجاد',
            'updated_at' => 'تاریخ بروزرسانی',
            'to_account' => 'حساب مقصد',
            'source_account' => 'حساب مبدا',
            'clock' => 'ساعت',
            'tracking_code' => 'کد رهگیری',
            'from_bank' => 'بانک مبدا',
            'to_bank' => 'بانک مقصد',
            'giver_name' => 'نام دهنده',
            'remittance_image' => 'تصویر حواله',
            'state' => 'وضعیت',
        ];

        return $labels[$field] ?? $field;
    }

    public function formatDataForDisplay($data)
    {
        if (!is_array($data)) {
            return [];
        }

        $formatted = [];

        foreach ($data as $key => $value) {
            if ($value === null) {
                $formatted[$key] = '<span class="text-gray-400 text-xs bg-gray-100 px-2 py-1 rounded">بدون مقدار</span>';
            } elseif ($value === '') {
                $formatted[$key] = '<span class="text-gray-400 text-xs bg-gray-100 px-2 py-1 rounded">خالی</span>';
            } elseif (is_numeric($value) && in_array($key, ['amount', 'buy_amount', 'sell_amount', 'withdrawal_amount', 'received_amount', 'tax_amount', 'eq_amount', 'exchange_rate', 'currency_rate'])) {
                // فرمت اعداد مالی با طراحی مینیمال
                $formatted[$key] = '<span class="font-mono text-emerald-600 bg-emerald-50 px-2 py-1 rounded text-sm border border-emerald-100">' . number_format($value, 2) . '</span>';
            } elseif (in_array($key, ['type', 'account_type', 'status', 'state'])) {
                // هایلایت برای مقادیر خاص
                $formatted[$key] = '<span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs border border-blue-100">' . $value . '</span>';
            } elseif (in_array($key, ['currency', 'from_currency', 'to_currency'])) {
                // هایلایت برای ارزها
                $formatted[$key] = '<span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs border border-green-100 font-mono">' . strtoupper($value) . '</span>';
            } elseif (strpos($key, 'date') !== false || strpos($key, '_at') !== false) {
                // فرمت تاریخ
                $formatted[$key] = '<span class="text-purple-600 bg-purple-50 px-2 py-1 rounded text-sm border border-purple-100">' . $value . '</span>';
            } elseif (is_string($value) && strlen($value) > 40) {
                // متن‌های طولانی
                $formatted[$key] = '<div class="relative group"><div class="truncate max-w-xs">' . substr($value, 0, 40) . '...</div><div class="absolute invisible group-hover:visible bg-gray-800 text-white text-xs rounded p-2 bottom-full mb-2 w-64 z-10 break-words">' . $value . '</div></div>';
            } elseif (is_bool($value)) {
                // مقادیر بولین
                $formatted[$key] = $value ? 
                    '<span class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-xs border border-emerald-100">فعال</span>' :
                    '<span class="bg-red-50 text-red-700 px-3 py-1 rounded-full text-xs border border-red-100">غیرفعال</span>';
            } else {
                $formatted[$key] = '<span class="text-gray-700">' . htmlspecialchars($value) . '</span>';
            }
        }

        return $formatted;
    }



    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        $transaction = Trash::findOrFail($this->confirmDeleteId);


        $transaction->delete();

        session()->flash('message', 'ترانزکشن موفقـــــانــــــه حذف گردید.');


        $this->confirmDeleteId = null;
    }


    public function render()
    {
        $query = Trash::with(['user', 'admin', 'registeredUser'])
            ->orderBy('created_at', 'desc');

        // اعمال فیلترها
        if ($this->filterAction) {
            $query->where('action', $this->filterAction);
        }

        if ($this->filterDocumentType) {
            $query->where('document_type', $this->filterDocumentType);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('document_discription', 'like', '%' . $this->search . '%')
                  ->orWhere('action', 'like', '%' . $this->search . '%')
                  ->orWhere('document_type', 'like', '%' . $this->search . '%');
            });
        }

        $trashRecords = $query->paginate(10);

        return view('livewire.sarafi.trash-edit', [
            'trashRecords' => $trashRecords
        ]);
    }
}