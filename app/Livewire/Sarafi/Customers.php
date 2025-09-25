<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Customers extends Component
{
    use WithFileUploads;
    
    public $customerId;
    public $fullname, $account, $category, $city, $phone, $tazkira, $whatsapp, $password;
    public $profile;
    public $newProfile;
    public $idCardImage;
    public $newIdCardImage;
    public $showSuccessModal = false;
    public $successMessage = '';
    public $autoGenerateAccount = true;
    
    public function mount($customerId = null)
    {
        $this->customerId = request('customerId') ?? $customerId;
        
        if ($this->customerId) {
            $this->loadCustomerData();
            $this->autoGenerateAccount = false;
        } else {
            $this->generateAccountNumber();
        }
    }
    
    private function generateAccountNumber()
    {
        do {
            $accountNumber = '6' . str_pad(mt_rand(1, 999999999999999), 15, '0', STR_PAD_LEFT);
            $accountNumber = substr($accountNumber, 0, 16);
        } while (Customer::where('account_number', $accountNumber)->exists());

        $this->account = $accountNumber;
    }
    
    public function generateNewAccountNumber()
    {
        $this->generateAccountNumber();
    }
    
    public function loadCustomerData()
    {
        Log::info('Loading customer data for ID: ' . $this->customerId);
        
        $customer = Customer::find($this->customerId);
        
        if ($customer) {
            $this->fullname = $customer->fullname;
            $this->account = $customer->account_number;
            $this->category = $customer->type;
            $this->city = $customer->city;
            $this->phone = $customer->phone;
            $this->tazkira = $customer->idcard_number;
            $this->whatsapp = $customer->whatsapp_number;
            $this->password = ''; 
            $this->profile = $customer->image;
            $this->idCardImage = $customer->id_card_image;
            
            Log::info('Customer data loaded:', [
                'fullname' => $this->fullname,
                'phone' => $this->phone,
                'city' => $this->city
            ]);
        } else {
            Log::error('Customer not found with ID: ' . $this->customerId);
        }
    }
    
    public function saveCustomer()
    {
        // اگر در حال ایجاد مشتری جدید هستیم و شماره حساب خالی است
        if (!$this->customerId && empty($this->account)) {
            $this->generateAccountNumber();
        }

        $validated = $this->validate([
            'fullname' => 'required|string|max:255',
            'account' => 'nullable|string|max:16|min:16|unique:sarafi.customers,account_number,' . $this->customerId,
            'category' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:sarafi.customers,phone,' . $this->customerId,
            'tazkira' => 'nullable|string|max:255|unique:sarafi.customers,idcard_number,' . $this->customerId,
            'whatsapp' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'newProfile' => 'nullable|image|max:2048',
            'newIdCardImage' => 'nullable|image|max:2048',
        ]);

        $data = [
            'fullname' => $this->fullname,
            'account_number' => $this->account,
            'type' => $this->category,
            'city' => $this->city,
            'phone' => $this->phone,
            'idcard_number' => $this->tazkira,
            'whatsapp_number' => $this->whatsapp,
            'created_by' => Auth::guard('sarafi')->id(),
        ];

        // مدیریت رمز عبور
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        // مدیریت آپلود عکس پروفایل جدید
        if ($this->newProfile) {
            $profilePath = $this->newProfile->store('customers/profiles', 'public');
            $data['image'] = $profilePath;
            Log::info('New profile image uploaded:', ['path' => $profilePath]);
        }

        // مدیریت آپلود عکس شناسنامه جدید
        if ($this->newIdCardImage) {
            $idCardPath = $this->newIdCardImage->store('customers/id-cards', 'public');
            $data['id_card_image'] = $idCardPath;
            Log::info('New ID card image uploaded:', ['path' => $idCardPath]);
        }

        if (!$this->customerId) {
            $data['created_by'] = Auth::id();
        }

        if ($this->customerId) {
            $customer = Customer::find($this->customerId);
            if ($customer) {
                $customer->update($data);
                $this->successMessage = 'مشتری با موفقیت ویرایش شد';
                Log::info('Customer updated with ID: ' . $this->customerId);
            }
        } else {
            Customer::create($data);
            $this->successMessage = 'مشتری جدید با موفقیت ایجاد شد';
            Log::info('New customer created');
        }
        
        $this->showSuccessModal = true;
    }
    
    public function removeProfileImage()
    {
        if ($this->newProfile) {
            $this->newProfile = null;
        } else if ($this->customerId && $this->profile) {
            $customer = Customer::find($this->customerId);
            if ($customer) {
                if ($customer->image && Storage::exists('public/' . $customer->image)) {
                    Storage::delete('public/' . $customer->image);
                }
                
                $customer->update(['image' => null]);
                $this->profile = null;
            }
        }
        
        session()->flash('message', 'عکس پروفایل با موفقیت حذف شد');
    }

    public function removeIdCardImage()
    {
        if ($this->newIdCardImage) {
            $this->newIdCardImage = null;
        } else if ($this->customerId && $this->idCardImage) {
            $customer = Customer::find($this->customerId);
            if ($customer) {
                if ($customer->id_card_image && Storage::exists('public/' . $customer->id_card_image)) {
                    Storage::delete('public/' . $customer->id_card_image);
                }
                
                $customer->update(['id_card_image' => null]);
                $this->idCardImage = null;
            }
        }
        
        session()->flash('message', 'عکس شناسنامه با موفقیت حذف شد');
    }

    public function resetForm()
    {
        return redirect()->route('sarafi.customer-table');
    }
    
    public function backToTable()
    {
        return redirect()->route('sarafi.customer-table');
    }
    
    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        return redirect()->route('sarafi.customer-table');
    }
    
    private function convertToEnglishNumbers($value)
    {
        if (!$value) return $value;
        
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        $value = str_replace($persian, $english, $value);
        $value = str_replace($arabic, $english, $value);
        
        return $value;
    }

    public function hydrate()
    {
        Log::info('Customer Component Hydrated', ['customerId' => $this->customerId]);
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
    
    public function render()
    {
        return view('livewire.sarafi.customers');
    }
}