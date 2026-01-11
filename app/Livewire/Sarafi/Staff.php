<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Staffs;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;
use NumberFormatter;

class Staff extends Component
{
    use WithPagination, WithFileUploads;

    // Search, modal and edit state
    public $search = '';
    public $modalOpen = false;
    public $editId = null;
    public $filterOpen = false;

    // Staff Form fields
    public $name, $fathername, $age, $gender = 'male', $phone, $address;
    public $image, $id_card, $document;
    public $job, $salary_amount, $contract_start, $contract_end;
    
    // For formatted display
    public $formatted_salary = '';
    public $salary_in_words = ''; // برای نمایش مبلغ به حروف

    // Temp URLs for preview
    public $tempImageUrl = null;
    public $tempIdCardUrl = null;
    public $tempDocumentUrl = null;

    // Alerts and delete confirmation
    public $alert = null;
    public $confirmDeleteId = null;

    // Filters
    public $filterGender = '';
    
    public $filterJob = '';

    // Cache keys
    protected $cacheKeys = [
        'staffs_list' => 'staffs_list_',
        'jobs_list' => 'jobs_list_',
    ];

    // Validation rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'fathername' => 'required|string|max:255',
        'age' => 'required|integer|min:18|max:80',
        'gender' => 'required|in:male,female',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:500',
        'image' => 'nullable|image|max:2048',
        'id_card' => 'nullable|image|max:2048',
        'document' => 'nullable|file|max:5120|mimes:pdf,doc,docx',
        'job' => 'required|string|max:100',
        'salary_amount' => 'required|integer|min:0',
        'contract_start' => 'required|date',
        'contract_end' => 'required|date|after_or_equal:contract_start',
    ];

    // Component initialization
    public function mount()
    {
     $todayJalali = Jalalian::now();
        $this->contract_start   = $todayJalali->format('Y-m-d'); // فرمت مشابه دیتابیس
        $this->contract_end = $todayJalali->format('Y-m-d');   // فرمت مشابه دیتابیس
        $this->gender = 'male';
    }


 
    // Reset form fields
    public function resetInputFields()
    {
        $this->reset([
            'name', 'fathername', 'age', 'gender', 'phone', 'address',
            'image', 'id_card', 'document', 'job', 'salary_amount',
            'contract_start', 'contract_end', 'editId', 'modalOpen',
            'formatted_salary', 'salary_in_words'
        ]);
        
        $this->tempImageUrl = null;
        $this->tempIdCardUrl = null;
        $this->tempDocumentUrl = null;
        
        // Reset validation errors
        $this->resetErrorBag();
        $this->resetValidation();
        
        // Reset to default values
        $this->contract_start = now()->format('Y-m-d');
        $this->contract_end = now()->addYear()->format('Y-m-d');
        $this->gender = 'male';
    }

    // Open modal for creating staff
    public function openCreateModal()
    {
        $this->resetInputFields();
        $this->modalOpen = true;
    }

    // Load staff for editing
    public function edit($id)
    {
        $staff = Staffs::findOrFail($id);

        $this->editId = $id;
        $this->name = $staff->name;
        $this->fathername = $staff->fathername;
        $this->age = $staff->age;
        $this->gender = $staff->gender;
        $this->phone = $staff->phone;
        $this->address = $staff->address;
        $this->job = $staff->job;
        $this->salary_amount = (int)$staff->salary_amount;
        $this->formatted_salary = $staff->salary_amount ? number_format($staff->salary_amount) : '';
        $this->contract_start = $staff->contract_start;
        $this->contract_end = $staff->contract_end;
        
        // تولید متن به حروف
        $this->generateSalaryInWords();

        $this->modalOpen = true;
    }

    // Preview images before upload
    public function updatedImage()
    {
        $this->validateOnly('image');
        $this->tempImageUrl = $this->image->temporaryUrl();
    }

    public function updatedIdCard()
    {
        $this->validateOnly('id_card');
        $this->tempIdCardUrl = $this->id_card->temporaryUrl();
    }

    public function updatedDocument()
    {
        $this->validateOnly('document');
    }

    // Remove image previews
    public function removeImage($type)
    {
        switch ($type) {
            case 'image':
                $this->image = null;
                $this->tempImageUrl = null;
                break;
            case 'id_card':
                $this->id_card = null;
                $this->tempIdCardUrl = null;
                break;
            case 'document':
                $this->document = null;
                break;
        }
    }

    // Handle salary input - convert formatted string to integer
    public function updatedFormattedSalary($value)
    {
        // تبدیل اعداد فارسی و عربی به انگلیسی
        $value = $this->convertPersianArabicToEnglish($value);
        
        // حذف تمام کاراکترهای غیرعددی (کاما، نقطه، فاصله)
        $cleaned = preg_replace('/[^\d]/', '', $value);
        
        // تبدیل به عدد صحیح
        $this->salary_amount = $cleaned ? (int)$cleaned : 0;
        
        // نمایش فرمت شده برای کاربر (حتی اگر خالی باشد)
        $this->formatted_salary = $this->salary_amount ? number_format($this->salary_amount) : '';
        
        // تولید متن به حروف
        $this->generateSalaryInWords();
    }
    
    // تابع برای تبدیل اعداد فارسی و عربی به انگلیسی
    private function convertPersianArabicToEnglish($value)
    {
        if (!$value) return $value;
        
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        $value = str_replace($persian, $english, $value);
        $value = str_replace($arabic, $english, $value);
        
        return $value;
    }
    
    // تولید مبلغ به حروف
    private function generateSalaryInWords()
    {
        if ($this->salary_amount > 0) {
            try {
                $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);
                $words = $formatter->format($this->salary_amount);
                
                // اصلاح برخی کلمات برای خوانایی بهتر
                $words = str_replace(['دویست', 'سیصد', 'پانصد'], ['دوصد', 'سه‌صد', 'پانصد'], $words);
                
                $this->salary_in_words = $words . ' افغانی';
            } catch (\Exception $e) {
                Log::error('Error generating salary in words: ' . $e->getMessage());
                $this->salary_in_words = 'خطا در تولید متن';
            }
        } else {
            $this->salary_in_words = '';
        }
    }

    // Apply filters
    public function applyFilter()
    {
        $this->filterOpen = false;
        $this->resetPage();
        
        // Clear cache when filtering
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        Cache::forget($this->cacheKeys['staffs_list'] . $adminId);
    }

    // Clear filters
    public function clearFilters()
    {
        $this->filterGender = '';
        $this->filterJob = '';
        $this->search = '';
        $this->applyFilter();
    }

    // Save or update staff
    public function save()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // اطمینان از اینکه salary_amount عدد صحیح است
        $this->salary_amount = $this->convertToInteger($this->salary_amount);
        
        // اعتبارسنجی
        $this->validate();

        $data = [
            'name' => $this->name,
            'fathername' => $this->fathername,
            'age' => $this->age,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'address' => $this->address,
            'job' => $this->job,
            'salary_amount' => $this->salary_amount,
            'contract_start' => $this->contract_start,
            'contract_end' => $this->contract_end,
            'admin_id' => $adminId,
            'user_id' => $user->id,
        ];

        // Handle file uploads
        if ($this->image) {
            $data['image'] = $this->uploadAndCompressImage($this->image, 'staff/images');
        }

        if ($this->id_card) {
            $data['id_card'] = $this->uploadAndCompressImage($this->id_card, 'staff/id_cards');
        }

        if ($this->document) {
            $data['document'] = $this->document->store('staff/documents', 'public');
        }

        // Delete old files if editing
        if ($this->editId) {
            $oldStaff = Staffs::find($this->editId);
            $this->deleteOldFiles($oldStaff, ['image', 'id_card', 'document']);
        }

        // Save or update staff
        if ($this->editId) {
            Staffs::find($this->editId)->update($data);
            $message = 'کارمند با موفقیت بروزرسانی شد.';
        } else {
            Staffs::create($data);
            $message = 'کارمند جدید با موفقیت ثبت شد.';
        }

        // Clear cache
        Cache::forget($this->cacheKeys['staffs_list'] . $adminId);
        Cache::forget($this->cacheKeys['jobs_list'] . $adminId);

        $this->alert = [
            'title' => 'موفقیت',
            'message' => $message,
            'type' => 'success'
        ];

        $this->modalOpen = false;
        $this->resetInputFields();
    }

    // Helper method to convert any input to integer
    private function convertToInteger($value)
    {
        if (is_null($value)) {
            return 0;
        }

        if (is_numeric($value)) {
            return (int)$value;
        }

        // حذف تمام کاراکترهای غیرعددی
        $cleaned = preg_replace('/[^\d]/', '', $value);
        
        return $cleaned ? (int)$cleaned : 0;
    }

    // Helper method for image upload and compression
    private function uploadAndCompressImage($file, $folder)
    {
        $path = $file->store($folder, 'public');
        $fullPath = storage_path('app/public/' . $path);

        try {
            $image = Image::make($fullPath);
            
            // Compress image if larger than 1MB
            if ($image->filesize() > 1024 * 1024) {
                $image->encode('jpg', 75); // 75% quality
                $image->save($fullPath);
            }

            // Resize if dimensions are too large
            if ($image->width() > 1200 || $image->height() > 1200) {
                $image->resize(1200, 1200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->save($fullPath);
            }
        } catch (\Exception $e) {
            Log::error('Image compression failed: ' . $e->getMessage());
        }

        return $path;
    }

    // Delete old files when updating
    private function deleteOldFiles($staff, $fields)
    {
        foreach ($fields as $field) {
            if ($staff->$field && Storage::disk('public')->exists($staff->$field)) {
                Storage::disk('public')->delete($staff->$field);
            }
        }
    }

    // Confirm deletion
    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function clearAlert()
    {
        $this->alert = null;
    }

    // Delete staff
    public function delete()
    {
        if ($this->confirmDeleteId) {
            $staff = Staffs::findOrFail($this->confirmDeleteId);
            
            // Delete associated files
            $this->deleteOldFiles($staff, ['image', 'id_card', 'document']);
            
            $staff->delete();
            
            // Clear cache
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;
            Cache::forget($this->cacheKeys['staffs_list'] . $adminId);
            Cache::forget($this->cacheKeys['jobs_list'] . $adminId);
            
            $this->alert = [
                'title' => 'موفقیت',
                'message' => 'کارمند با موفقیت حذف شد.',
                'type' => 'success'
            ];
            
            $this->confirmDeleteId = null;
        }
    }

    // Print staff information
    public function print($id)
    {
        $staff = Staffs::findOrFail($id);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [80, 280],
            'directionality' => 'rtl',
            'margin_top' => 2,
            'margin_bottom' => 2,
            'margin_left' => 2,
            'margin_right' => 2,
            'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                public_path('fonts'),
            ]),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'Shabnam' => [
                    'R' => 'Shabnam-FD.ttf',
                ],
            ],
            'default_font' => 'Shabnam',
        ]);

        $mpdf->SetAutoPageBreak(false);

        $html = view('pdf.Sarafi.staff-print', compact('staff'))->render();
        $mpdf->WriteHTML($html);

        $fileName = $staff->name . '_کارمند.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }

    // Get paginated staff with caching
   public function getStaffsProperty()
{
    $user = Auth::guard('sarafi')->user();
    $adminId = $user->admin_id ?? $user->id;
    
    // 🔴 حذف کش و مستقیم گرفتن از دیتابیس
    $query = Staffs::where('admin_id', $adminId);

    if ($this->search) {
        $query->where(function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('fathername', 'like', '%' . $this->search . '%')
              ->orWhere('phone', 'like', '%' . $this->search . '%')
              ->orWhere('job', 'like', '%' . $this->search . '%');
        });
    }

    if ($this->filterGender) {
        $query->where('gender', $this->filterGender);
    }

    if ($this->filterJob) {
        $query->where('job', 'like', '%' . $this->filterJob . '%');
    }

    return $query->orderBy('created_at', 'desc')->paginate(10);
}
    // Get unique jobs for filter
    public function getJobsProperty()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        $cacheKey = $this->cacheKeys['jobs_list'] . $adminId;

        return Cache::remember($cacheKey, 3600, function () use ($adminId) {
            return Staffs::where('user_id', $adminId)
                ->select('job')
                ->distinct()
                ->orderBy('job')
                ->pluck('job')
                ->toArray();
        });
    }

    public function render()
    {
        $staffs = $this->staffs;
        $jobs = $this->jobs;
        $genders = [
            'male' => 'مرد',
            'female' => 'زن'
        ];

        return view('livewire.sarafi.staff', compact('staffs', 'jobs', 'genders'));
    }
}