<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;

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

    // اضافه کردن تنظیمات compression
    public $compressionQuality = 80; // کیفیت بین 0-100
    public $maxWidth = 800;
    public $maxHeight = 800;

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

    // تابع جدید برای compress کردن تصاویر
    private function compressAndStoreImage($uploadedFile, $storagePath)
    {
        // ایجاد instance از Intervention Image
        $image = Image::make($uploadedFile->getRealPath());

        // تغییر سایز تصویر اگر بزرگتر از حد مجاز است
        $image->resize($this->maxWidth, $this->maxHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // encode کردن تصویر با کیفیت مشخص
        $image->encode($uploadedFile->getClientOriginalExtension(), $this->compressionQuality);

        // تولید نام فایل
        $filename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
        $fullPath = $storagePath . '/' . $filename;

        // ذخیره تصویر compressed
        Storage::disk('public')->put($fullPath, $image->stream());

        return $fullPath;
    }

    public function saveCustomer()
    {
        if (!$this->customerId && empty($this->account)) {
            $this->generateAccountNumber();
        }
        $this->account = $this->convertToEnglishNumbers($this->account);
        $this->tazkira = $this->convertToEnglishNumbers($this->tazkira);
        $this->phone = $this->convertToEnglishNumbers($this->phone);
        $this->whatsapp = $this->convertToEnglishNumbers($this->whatsapp);

        $validated = $this->validate([
            'fullname' => 'required|string|max:255',
            'account' => 'nullable|string|max:16|min:16|unique:sarafi.customers,account_number,' . $this->customerId,
            'category' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:sarafi.customers,phone,' . $this->customerId,
            'tazkira' => '|nullable|string|max:255|unique:sarafi.customers,idcard_number,' . $this->customerId,
            'whatsapp' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'newProfile' => 'nullable|image|max:5120', 
            'newIdCardImage' => 'nullable|image|max:5120',
        ], [
            'fullname.required' => __('messages.validation_fullname_required'),
            'account.required' => __('messages.validation_account_required'),
            'city.required' => __('messages.validation_city_required'),
            'phone.required' => __('messages.validation_phone_required'),
            'tazkira.required' => __('messages.validation_tazkira_required'),
            'password.required' => __('messages.validation_password_required'),
        ]);
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $data = [
            'fullname' => $this->fullname,
            'account_number' => $this->account,
            'type' => $this->category,
            'city' => $this->city,
            'phone' => $this->phone,
            'idcard_number' => $this->tazkira,
            'whatsapp_number' => $this->whatsapp,
            'user_id'          => $user->id,
            'admin_id'         => $adminId,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        // مدیریت آپلود عکس پروفایل جدید با compression
        if ($this->newProfile) {
            $profilePath = $this->compressAndStoreImage($this->newProfile, 'customers/profiles');
            $data['image'] = $profilePath;
            Log::info('New profile image uploaded and compressed:', ['path' => $profilePath]);
        }

        // مدیریت آپلود عکس شناسنامه جدید با compression
        if ($this->newIdCardImage) {
            $idCardPath = $this->compressAndStoreImage($this->newIdCardImage, 'customers/id-cards');
            $data['id_card_image'] = $idCardPath;
            Log::info('New ID card image uploaded and compressed:', ['path' => $idCardPath]);
        }

        if (!$this->customerId) {
            $data['created_by'] = Auth::id();
        }

        if ($this->customerId) {
            $customer = Customer::find($this->customerId);
            if ($customer) {
                $customer->update($data);
                session()->flash('message', __('messages.customer_updated'));
                Log::info('Customer updated with ID: ' . $this->customerId);
            }
        } else {
            Customer::create($data);
            session()->flash('message', __('messages.customer_created'));
            Log::info('New customer created');
        }

        $this->showSuccessModal = true;
        $this->resetForm();
        $this->backToTable();
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

        session()->flash('message',  __('messages.customer_update'));
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
        session()->flash('message',  __('messages.idcard_removed'));
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

    public function updatedPassword($value)
    {
        $this->password = $this->convertToEnglishNumbers($value);
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
