<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Sarafi\Customer;
use Illuminate\Support\Str;

class Customers extends Component
{
    use WithFileUploads;

    public $customerId;
    public $name, $account, $category, $city, $phone, $tazkira, $whatsapp;
    public $profile;
    public $successMessage = null;
    public $showSuccessModal = false;
    protected $listeners = ['edit-customer' => 'editCustomer'];



    protected $rules = [
            'name' => 'required|string|max:255',
            'account' => 'nullable|string|unique:sarafi.customers,account_number',
            'category' => 'nullable|string',
            'city' => 'required|string|max:100',
            'phone' => 'required|string', 
            'tazkira' => 'nullable|string|unique:sarafi.customers,idcard_number',
            'whatsapp' => 'nullable|string',
            'profile' => 'nullable|image|max:1024',
    ];

    protected $messages = [
        'name.required' => 'نام مشتری الزامی است.',
        'account.unique' => 'این شماره حساب قبلاً ثبت شده است.',
        'tazkira.unique' => 'شماره تذکره قبلاً ثبت شده است.',
        'profile.image' => 'فایل انتخابی باید تصویر باشد.',
        'city.required' => 'شهر مشتری الزامی است.',
        'phone.required' => 'شماره تلفن الزامی است.',
    ];

    public function mount($customerId = null)
    {
        if ($customerId) {
            $this->customerId = $customerId;
            $customer = Customer::findOrFail($customerId);
            $this->name = $customer->fullname;
            $this->account = $customer->account_number;
            $this->category = $customer->type;
            $this->city = $customer->city;
            $this->phone = $customer->phone;
            $this->tazkira = $customer->idcard_number;
            $this->whatsapp = $customer->whatsapp_number;
            $this->profile = $customer->image;
        }
    }

    // Validation لحظه‌ای
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
public function saveCustomer()
{
    $rules = $this->rules;

    if ($this->customerId) {
        $rules['account'] = 'nullable|string|unique:sarafi.customers,account_number,' . $this->customerId;
        $rules['tazkira'] = 'nullable|string|unique:sarafi.customers,idcard_number,' . $this->customerId;
    }

    $validatedData = $this->validate($rules);

    $data = [
        'fullname' => $validatedData['name'],
        'account_number' => $validatedData['account'] ?? null,
        'type' => $validatedData['category'] ?? null,
        'city' => $validatedData['city'],
        'phone' => $validatedData['phone'],
        'idcard_number' => $validatedData['tazkira'] ?? null,
        'whatsapp_number' => $validatedData['whatsapp'] ?? null,
    ];

    if ($this->profile && is_object($this->profile)) {
        $filename = Str::slug($this->name) . '_' . time() . '.' . $this->profile->getClientOriginalExtension();
        $data['image'] = $this->profile->storeAs('customers', $filename, 'public');
    }

    Customer::updateOrCreate(
        ['id' => $this->customerId],
        $data
    );


    $this->successMessage = $this->customerId
        ? 'مشتری با موفقیت بروزرسانی شد.'
        : 'مشتری با موفقیت اضافه شد.';

    $this->showSuccessModal = true;

    // 📌 ریست فرم
    $this->resetExcept(['successMessage', 'showSuccessModal']);
    $this->customerId = null;
    
}


private function convertToEnglishNumbers($value)
{
    $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    $english = ['0','1','2','3','4','5','6','7','8','9'];
    return str_replace($persian, $english, $value);
}

public function updatedAccount($value)
{
    $this->account = $this->convertToEnglishNumbers($value);
  

    }
  public function updatedTazkira($value)
{
    $this->tazkira = $this->convertToEnglishNumbers($value);
}

public function updatedPhone($value)
{
    $this->phone = $this->convertToEnglishNumbers($value);
}

public function updatedWhatsapp($value)
{
    $this->whatsapp = $this->convertToEnglishNumbers($value);
}


   




public function editCustomer($id)
{
    $customer = Customer::findOrFail($id);
    $this->customerId = $customer->id;
    $this->name = $customer->fullname;
    $this->account = $customer->account_number;
    $this->category = $customer->type;
    $this->city = $customer->city;
    $this->phone = $customer->phone;
    $this->tazkira = $customer->idcard_number;
    $this->whatsapp = $customer->whatsapp_number;
    $this->profile = $customer->image;
}

    
    public function render()
    {
        return view('livewire.sarafi.customers');
    }
}
