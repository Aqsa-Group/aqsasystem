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

    public function editCustomer($id)
    {
        return redirect()->route('sarafi.customer-create', ['customerId' => $id]);
    }

    public function createCustomer()
    {
        return redirect()->route('sarafi.customer-create');
    }
   public function render()
{
    $user = Auth::guard('sarafi')->user();

    if (!$user) {
        return view('livewire.sarafi.customers-table', [
            'customers' => collect(),
        ]);
    }

    $adminId = $user->admin_id ?? $user->id;

    $relatedUserIds = \App\Models\Sarafi\User::where('admin_id', $adminId)->pluck('id')->push($adminId);

    $customers = Customer::query()
        ->where(function ($query) use ($adminId, $relatedUserIds) {
        
            $query->where('admin_id', $adminId)
               
                ->orWhereHas('transactions', function ($t) use ($relatedUserIds) {
                    $t->whereIn('user_id', $relatedUserIds)
                      ->orWhereIn('admin_id', $relatedUserIds);
                });
        })
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

    return view('livewire.sarafi.customers-table', compact('customers'));
}

}
