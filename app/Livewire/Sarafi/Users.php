<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Mpdf\Mpdf;
use App\Models\Sarafi\ImpersonationToken;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Users extends Component
{
    use WithPagination, WithFileUploads;

    // Search, modal and edit state
    public $search = '';
    public $modalOpen = false;
    public $editId = null;
    public $filterOpen = false;

    // Form fields
    public $name, $lastname, $username, $password, $role, $sarafi_name, $address, $address2, $address3, $phone, $phone2, $phone3, $user_limition, $zone;
    public $user_image; // فیلد جدید برای آپلود تصویر
    public $whatsapp_notification = 0; // فیلد جدید برای نوتیفیکیشن واتساپ
    public $temp_image_url = null; // برای نمایش تصویر موقت
    
    // ✅ متغیر جدید برای ذخیره تصویر فعلی کاربر
    public $current_user_image = null;

    // Alerts and delete confirmation
    public $alert = null;
    public $confirmDeleteId = null;

    // Filters
    public $filterRole = '';
    public $filterSarafi = '';

    // Validation rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:sarafi.users,username',
        'password' => 'nullable|string|min:6',
        'role' => 'required',
        'user_limition' => 'nullable|integer|min:0',
        'zone' => 'required|string|max:255',
        'user_image' => 'nullable|image|max:2048', // حداکثر 2MB
        'whatsapp_notification' => 'boolean',
    ];

    public $roles = [
        'superadmin' => 'سوپر ادمین',
        'admin' => 'مدیر',
        'warehouse_manager' => 'خزانه دار',
        'internal_officer' => 'مسوول احواله جات داخلی',
        'external_officer' => 'مسوول احواله جات خارجی',
    ];

    // -------------------------
    // Component initialization
    // -------------------------
    public function mount()
    {
        $this->setDefaultValues();
            $this->phone = '937';
            $this->phone2 = '937';
            $this->phone3 = '937';

    }


    private function normalizePhone($value)
{
    $value = $this->convertToEnglishNumbers($value);
    $value = preg_replace('/[^0-9]/', '', $value);

    if (strlen($value) < 3) {
        return '937';
    }

    if (!str_starts_with($value, '937')) {
        $value = '937' . substr($value, 3);
    }

    return $value;
}





    private function setDefaultValues()
    {
        $currentUser = Auth::guard('sarafi')->user();

        if ($currentUser->role === 'admin') {
            $this->sarafi_name = $currentUser->sarafi_name;
            $this->address = $currentUser->address;
        } else {
            $this->zone = $currentUser->zone;
        }
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


    public function updatedPassword($value)
    {
        $this->password = $this->convertToEnglishNumbers($value);
    }

    // -------------------------
    // Reset form fields
    // -------------------------
    public function resetInputFields()
    {
        $this->name = '';
        $this->lastname = '';
        $this->username = '';
        $this->password = '';
        $this->role = '';
        $this->phone = null;
        $this->phone2 = null;
        $this->phone3 = null;
        $this->user_limition = null;
        $this->zone = '';
        $this->user_image = null;
        $this->whatsapp_notification = 0;
        $this->temp_image_url = null;
        $this->current_user_image = null; // ✅ پاک کردن تصویر فعلی
        $this->editId = null;
        $this->modalOpen = false;
    }

    // -------------------------
    // Open modal for creating user
    // -------------------------
    public function openCreateModal()
    {
        $this->resetInputFields();
        $this->zone = Auth::guard('sarafi')->user()->zone;
        $this->setDefaultValues();
        $this->modalOpen = true;
    }

    // -------------------------
    // Load user for editing
    // -------------------------
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->editId = $id;
        $this->name = $user->name;
        $this->lastname = $user->lastname;
        $this->username = $user->username;
        $this->role = $user->role;
        $this->sarafi_name = $user->sarafi_name ?? '';
        $this->address = $user->address ?? null;
        $this->address2 = $user->address2 ?? null;
        $this->address3 = $user->address3 ?? null;
        $this->phone = $user->phone ?? null;
        $this->phone2 = $user->phone2 ?? null;
        $this->phone3 = $user->phone3 ?? null;
        $this->user_limition = $user->user_limition ?? null;
        $this->whatsapp_notification = $user->whatsapp_notification ?? 0;
        $this->zone = $user->zone;
        $this->current_user_image = $user->user_image; // ✅ ذخیره تصویر فعلی کاربر
        $this->modalOpen = true;
    }

    // -------------------------
    // Preview image before upload
    // -------------------------
    public function updatedUserImage()
    {
        $this->validate([
            'user_image' => 'image|max:2048',
        ]);
        
        $this->temp_image_url = $this->user_image->temporaryUrl();
        $this->current_user_image = null; // وقتی تصویر جدید آپلود شد، تصویر قبلی را پاک کن
    }

    // -------------------------
    // Remove image preview
    // -------------------------
    public function removeImage()
    {
        $this->user_image = null;
        $this->temp_image_url = null;
        $this->current_user_image = null; // تصویر فعلی را هم پاک کن
    }

    // -------------------------
    // Search users
    // -------------------------
    public function searchUser()
    {
        $this->resetPage();
    }

    // -------------------------
    // Apply role/sarafi filters
    // -------------------------
    public function applyFilter()
    {
        $this->filterOpen = false;
    }

    // -------------------------
    // Save or update user
    // -------------------------
    public function save()
    {
        $currentUser = Auth::guard('sarafi')->user();
        $rules = $this->rules;

        if ($this->editId) {
            $rules['username'] = 'required|string|max:255|unique:sarafi.users,username,' . $this->editId;
            $rules['user_image'] = 'nullable|image|max:2048';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'role' => $this->role,
            'sarafi_name' => $this->sarafi_name,
            'address' => $this->address,
            'address2' => $this->address2,
            'address3' => $this->address3,
            'phone'  => $this->phone  ?: null,
            'phone2' => $this->phone2 ?: null,
            'phone3' => $this->phone3 ?: null,
            'status' => $this->editId ? User::find($this->editId)->status : 0,
            'zone' => $this->zone,
            'whatsapp_notification' => $this->whatsapp_notification ? 1 : 0,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        // Handle image upload and compression
        if ($this->user_image) {
            // Delete old image if exists
            if ($this->editId) {
                $oldUser = User::find($this->editId);
                if ($oldUser && $oldUser->user_image) {
                    $oldUser->deleteOldImage();
                }
            }
            
            // Compress and save new image
            $imagePath = User::compressAndSaveImage($this->user_image, $this->editId);
            if ($imagePath) {
                $data['user_image'] = $imagePath;
            }
        } elseif ($this->editId && $this->current_user_image) {
            // اگر در حالت ویرایش هستیم و تصویر جدیدی آپلود نشده، تصویر قبلی را نگه دار
            $data['user_image'] = $this->current_user_image;
        }

        // Superadmin rules
        if ($currentUser && $currentUser->role === 'superadmin') {
            $data['admin_id'] = $this->role === 'admin' && !$this->editId ? null : null;
            if ($this->role === 'admin' && !$this->editId) {
                $data['user_limition'] = $this->user_limition ?? 0;
            }
        }

        // Admin rules
        if ($currentUser && $currentUser->role === 'admin' && !$this->editId) {
            $userCount = User::where('admin_id', $currentUser->id)->count();
            if ($userCount >= ($currentUser->user_limition ?? 0)) {
                $this->alert = ['title' => 'Error', 'message' => 'Maximum users reached.'];
                return;
            }

            $data['admin_id'] = $currentUser->id;
            $data['user_limition'] = 0;

            if ($this->role === 'superadmin') {
                $this->alert = ['title' => 'Error', 'message' => 'Admin cannot create superadmin.'];
                return;
            }
        }

        // Save or update
        if ($this->editId) {
            User::find($this->editId)->update($data);
            $this->alert = [
                'title' => __('messages.Success'),
                'message' => __('messages.user_updated')
            ];
        } else {
            User::create($data);
            $this->alert = ['title' => __('messages.Success'), 'message' => __('messages.user_created')];
        }

        $this->modalOpen = false;
        $this->resetInputFields();
    }

    // -------------------------
    // Confirm deletion
    // -------------------------
    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function clearAlert()
    {
        $this->alert = null;
    }

    // -------------------------
    // Delete user
    // -------------------------
    public function delete()
    {
        if ($this->confirmDeleteId) {
            $user = User::findOrFail($this->confirmDeleteId);
            
            // Delete user image if exists
            if ($user->user_image) {
                $user->deleteOldImage();
            }
            
            $user->delete();
            
            $this->alert = [
                'title' => __('messages.Success'),
                'message' => __('messages.user_deleted')
            ];
            $this->confirmDeleteId = null;
        }
    }

    public function print($id)
    {
        $user = User::findOrFail($id);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [80, 135],
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

        $html = view('pdf.Sarafi.user-print', compact('user'))->render();
        $mpdf->WriteHTML($html);

        $fileName = $user->name . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }

    // -------------------------
    // Get paginated users
    // -------------------------
    public function getUsersProperty()
    {
        $currentUser = Auth::guard('sarafi')->user();
        $query = User::query();

        // Role-based filtering
        if ($currentUser->role === 'admin') {
            $query->where('admin_id', $currentUser->id)
                ->orWhere('id', $currentUser->id);
        } elseif ($currentUser->role !== 'superadmin') {
            $query->where('id', $currentUser->id);
        }

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('lastname', 'like', '%' . $this->search . '%')
                    ->orWhere('username', 'like', '%' . $this->search . '%');
            });
        }

        // **Filters**
        if ($this->filterRole) {
            $query->where('role', $this->filterRole);
        }

        if ($this->filterSarafi) {
            $query->where('sarafi_name', $this->filterSarafi);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    // -------------------------
    // Get unique roles for filter
    // -------------------------
    public function getRolesProperty()
    {
        return User::select('role')->distinct()->pluck('role')->toArray();
    }

 

    public function updatedPhone($value)
{
    $this->phone = $this->normalizePhone($value);
        $this->phone3 = $this->convertToEnglishNumbers($value);

    

}

public function updatedPhone2($value)
{
    $this->phone2 = $this->normalizePhone($value);
        $this->phone3 = $this->convertToEnglishNumbers($value);

}

public function updatedPhone3($value)
{
    $this->phone3 = $this->normalizePhone($value);
        $this->phone3 = $this->convertToEnglishNumbers($value);

}

    // -------------------------
    // Get unique sarafis for filter
    // -------------------------
    public function getSarafisProperty()
    {
        return User::select('sarafi_name')->whereNotNull('sarafi_name')->distinct()->pluck('sarafi_name')->toArray();
    }

    public function loginAsInNewWindow($userId)
    {
        $currentUser = Auth::guard('sarafi')->user();

        if ($currentUser->role !== 'superadmin') {
            abort(403);
        }

        $token = Str::random(64);

        ImpersonationToken::create([
            'super_admin_id' => $currentUser->id,
            'user_id'        => $userId,
            'token'          => hash('sha256', $token),
            'expires_at'     => now()->addMinutes(5),
        ]);

        $url = route('impersonate.login', ['token' => $token]);

        $this->dispatch('open-new-window', url: $url);
    }

    public function render()
    {
        $currentUser = Auth::guard('sarafi')->user();
        $users = $this->users;
        return view('livewire.sarafi.users', compact('users', 'currentUser'));
    }
}