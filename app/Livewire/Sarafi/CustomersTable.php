<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sarafi\Customer;
use Illuminate\Support\Facades\Auth;

class CustomersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDelete = null;

    public function mount()
    {
        // بررسی احراز هویت
        if (!Auth::guard('sarafi')->check()) {
            return redirect()->route('sarafi.login.form');
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

    // انتقال به صفحه ویرایش با پارامتر ID
    public function editCustomer($id)
    {
        return redirect()->route('sarafi.customer-create', ['customerId' => $id]);
    }

    // انتقال به صفحه ایجاد مشتری جدید
    public function createCustomer()
    {
        return redirect()->route('sarafi.customer-create');
    }

    public function render()
    {
        $customers = Customer::when($this->search, function ($query) {
            $query->where('fullname', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('account_number', 'like', '%' . $this->search . '%')
                  ->orWhere('city', 'like', '%' . $this->search . '%');
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.sarafi.customers-table', compact('customers'));
    }
}