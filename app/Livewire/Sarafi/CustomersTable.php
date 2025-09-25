<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sarafi\Customer;

class CustomersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDelete = false;
    public $deleteId = null;

    protected $listeners = ['customerSaved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // 📌 نمایش مودال حذف
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    // 📌 حذف مشتری
    public function deleteCustomer()
    {
        Customer::findOrFail($this->deleteId)->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
    }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, function ($query) {
                $query->where('fullname', 'like', "%{$this->search}%")
                    ->orWhere('account_number', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%")
                    ->orWhere('city', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('livewire.sarafi.customers-table', [
            'customers' => $customers,
        ]);
    }
}
