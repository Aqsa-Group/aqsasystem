<?php

namespace App\Livewire\ToolsPanel;

use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Mpdf\Mpdf;
use Livewire\Component;
use App\Models\Tools\Staffs;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Staff extends Component
{
    use WithPagination , WithFileUploads;

    // Search, modal and edit state
    public $search = '';
    public $modalOpen = false;
    public $editId = null;
    public $filterOpen = false;

    public $profile;
    public $newProfile;
    public $idCardImage;
    public $newIdCardImage;
    public $compressionQuality = 80; 
    public $maxWidth = 800;
    public $maxHeight = 800;

    // Form fields
    public $name, $lastname, $job, $address, $phone, $salary;

    // Alerts and delete confirmation
    public $alert = null;
    public $confirmDeleteId = null;
    public $currentUser;

    // Filters
    public $filterJob = '';

    // Validation rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'job' => 'required|string|max:255',
        'salary' => 'required|numeric',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->currentUser = Auth::guard('tools')->user();
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

    public function updatedPhone($value)
    {
        $this->phone = $this->convertToEnglishNumbers($value);
    }

    public function updatedSalary($value)
    {
        $this->salary = $this->convertToEnglishNumbers($value);
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->lastname = '';
        $this->job = '';
        $this->phone = '';
        $this->salary = '';
        $this->address = '';
        $this->newProfile = null;
        $this->newIdCardImage = null;
        $this->profile = null;
        $this->idCardImage = null;
        $this->modalOpen = false;
        $this->editId = null;
        $this->resetErrorBag();
    }

    public function resetForm()
    {
        $this->resetInputFields();
    }

    public function openCreateModal()
    {
        $this->resetInputFields();
        $this->modalOpen = true;
    }

    public function edit($id)
    {
        $staff = Staffs::findOrFail($id);

        $this->editId = $id;
        $this->name = $staff->name;
        $this->lastname = $staff->lastname;
        $this->job = $staff->job;
        $this->salary = $staff->salary;
        $this->address = $staff->address ?? '';
        $this->phone = $staff->phone ?? '';
        $this->profile = $staff->image; // اصلاح شده: image به جای profile
        $this->idCardImage = $staff->id_card_image;
        $this->modalOpen = true;
    }

    public function searchUser()
    {
        $this->resetPage();
    }

    public function applyFilter()
    {
        $this->filterOpen = false;
        $this->resetPage();
    }

    private function compressAndStoreImage($uploadedFile, $storagePath)
    {
        if (!$uploadedFile) {
            return null;
        }

        $image = Image::make($uploadedFile->getRealPath());

        $image->resize($this->maxWidth, $this->maxHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $extension = strtolower($uploadedFile->getClientOriginalExtension());
        if ($extension == 'jpg' || $extension == 'jpeg') {
            $image->encode('jpg', $this->compressionQuality);
            $filename = uniqid() . '.jpg';
        } else {
            $image->encode($extension, $this->compressionQuality);
            $filename = uniqid() . '.' . $extension;
        }

        $fullPath = $storagePath . '/' . $filename;

        Storage::disk('public')->put($fullPath, $image->stream());

        return $fullPath;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'lastname' => $this->lastname,
            'job' => $this->job,
            'address' => $this->address,
            'phone' => $this->phone,
            'salary' => $this->salary,
        ];

        // Handle profile image - اصلاح شده: image به جای profile
        if ($this->newProfile) {
            $data['image'] = $this->compressAndStoreImage($this->newProfile, 'staff/profiles');
        }

        // Handle ID card image
        if ($this->newIdCardImage) {
            $data['id_card_image'] = $this->compressAndStoreImage($this->newIdCardImage, 'staff/id_cards');
        }

        // Add user info for new records
        if (!$this->editId) {
            $data['admin_id'] = $this->currentUser->id;
            $data['created_by'] = $this->currentUser->id;
        }

        // Save or update
        try {
            if ($this->editId) {
                Staffs::findOrFail($this->editId)->update($data);
                $message = __('messages.staff_updated');
            } else {
                Staffs::create($data);
                $message = __('messages.staff_created');
            }

            $this->alert = [
                'title' => __('messages.Success'),
                'message' => $message
            ];

            $this->modalOpen = false;
            $this->resetInputFields();
            
        } catch (\Exception $e) {
            Log::error('Error saving staff: ' . $e->getMessage());
            $this->alert = [
                'title' => 'Error',
                'message' => 'خطا در ذخیره اطلاعات: ' . $e->getMessage()
            ];
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function clearAlert()
    {
        $this->alert = null;
    }

    public function delete()
    {
        if ($this->confirmDeleteId) {
            try {
                Staffs::findOrFail($this->confirmDeleteId)->delete();
                $this->alert = [
                    'title' => __('messages.Success'),
                    'message' => __('messages.staff_deleted')
                ];
                $this->confirmDeleteId = null;
            } catch (\Exception $e) {
                $this->alert = [
                    'title' => 'Error',
                    'message' => 'خطا در حذف کارمند: ' . $e->getMessage()
                ];
            }
        }
    }

    public function print($id)
    {
        $staff = Staffs::findOrFail($id);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [80, 105],
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
                    'R' => 'amiri-regular.ttf',
                ],
            ],
            'default_font' => 'Shabnam',
        ]);

        $mpdf->SetAutoPageBreak(false);

        $html = view('pdf.Tools.staffs-print', compact('staff'))->render();
        $mpdf->WriteHTML($html);

        $fileName = $staff->name . '_' . $staff->lastname . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }

    public function getStaffsProperty()
    {
        $currentUser = $this->currentUser;
        $query = Staffs::query();

        // Role-based filtering
        if ($currentUser->role === 'admin') {
            $query->where('admin_id', $currentUser->id);
        } elseif ($currentUser->role !== 'superadmin') {
            $query->where('admin_id', $currentUser->id);
        }

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('lastname', 'like', '%' . $this->search . '%')
                  ->orWhere('job', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by job
        if ($this->filterJob) {
            $query->where('job', $this->filterJob);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public function getJobsProperty()
    {
        return Staffs::select('job')->distinct()->pluck('job')->toArray();
    }

    public function render()
    {
        $this->currentUser = Auth::guard('tools')->user();
        return view('livewire.tools-panel.staff', [
            'staffs' => $this->staffs,
            'jobs' => $this->jobs,
        ]);
    }
}