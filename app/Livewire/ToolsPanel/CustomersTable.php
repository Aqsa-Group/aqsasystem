<?php

namespace App\Livewire\ToolsPanel;

use App\Models\Tools\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Mpdf\Mpdf;

class CustomersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDelete = null;
    public $selectedCustomers = [];



    public $selectAll = false;

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedCustomers = $this->customers->pluck('id')->toArray();
        } else {
            $this->selectedCustomers = [];
        }
    }

    public function mount()
    {
        if (!Auth::guard('tools')->check()) {
            return redirect()->route('tools.login.form');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function deleteCustomer()
    {
        if ($this->confirmingDelete) {
            Customer::find($this->confirmingDelete)->delete();
            $this->confirmingDelete = null;
            session()->flash('message', __('messages.customer_deleted'));
        }
    }

    public function editCustomer($id)
    {
        return redirect()->route('tools.customer-create', ['customerId' => $id]);
    }

    public function createCustomer()
    {
        return redirect()->route('tools.customer-create');
    }


  public function print($id)
{
    $customer = Customer::findOrFail($id);

    // ایجاد متن برای QR Code با تمام اطلاعات مشتری
    $qrText = "صرافی زرین - Zareen Exchange\n";
    $qrText .= "=======================\n";
    $qrText .= "نام کامل: " . ($customer->fullname ?? '---') . "\n";
    $qrText .= "شماره تماس: " . ($customer->phone ?? '---') . "\n";
    $qrText .= "شهر: " . ($customer->city ?? '---') . "\n";
    $qrText .= "کد ملی: " . ($customer->idcard_number ?? '---') . "\n";
    $qrText .= "شماره حساب: " . ($customer->account_number ?? '---') . "\n";
    $qrText .= "واتساپ: " . ($customer->whatsapp_number ?? '---') . "\n";
    $qrText .= "نوع مشتری: " . ($customer->type ?? '---') . "\n";
    $qrText .= "تاریخ ثبت: " . ($customer->created_at ? $customer->created_at->format('Y/m/d') : '---') . "\n";
    $qrText .= "=======================\n";
    $qrText .= "www.zareen-exchange.com";

    // استفاده از سرویس آنلاین QR Code
    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" . urlencode($qrText);

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => [125, 104],
        'directionality' => 'ltr',
        'margin_top' => 2,
        'margin_bottom' => 2,
        'margin_left' => 2,
        'margin_right' => 2,
        'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
            public_path('fonts'),
        ]),
        'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
            'Shabnam' => [
                'R' => 'amiri-regular.ttf',
            ],
        ],
        'default_font' => 'Shabnam',
    ]);

    $mpdf->SetAutoPageBreak(false);

    $html = view('pdf.Tools.customer-card', compact('customer', 'qrCodeUrl'))->render();
    $mpdf->WriteHTML($html);

    $fileName = $customer->fullname . '.pdf';

    return response()->streamDownload(function () use ($mpdf) {
        echo $mpdf->Output('', 'S');
    }, $fileName);
}



public function render()
{
    $user = Auth::guard('tools')->user();

    if (!$user) {
        return view('livewire.tools-panel.customers-table', [
            'customers' => collect(),
        ]);
    }

    $adminId = $user->admin_id ?? $user->id;

    $relatedUserIds = \App\Models\Tools\User::where('admin_id', $adminId)
        ->pluck('id')
        ->push($adminId);

    $customers = Customer::query()
        ->whereIn('admin_id', $relatedUserIds)
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('account_number', 'like', '%' . $this->search . '%')
                    ->orWhere('city', 'like', '%' . $this->search . '%');
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Temporary log
    Log::debug('Customers fetched', [
        'user_id' => $user->id,
        'admin_id' => $adminId,
        'relatedUserIds' => $relatedUserIds,
        'customers_count' => $customers->count(),
        'customers_total' => $customers->total(),
    ]);

    return view('livewire.tools-panel.customers-table', compact('customers'));
}


}
