<?php

namespace App\Livewire\ToolsPanel;

use App\Models\Tools\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Mpdf\Mpdf;

class Users extends Component
{


    use WithPagination;

    // Search, modal and edit state
    public $search = '';
    public $modalOpen = false;
    public $editId = null;
    public $filterOpen = false;


    // Form fields
    public $name, $lastname, $username, $password, $role, $company_name, $address, $phone, $user_limition;

    // Alerts and delete confirmation
    public $alert = null;
    public $confirmDeleteId = null;
public $currentUser;

    // Filters
    public $filterRole = '';
    public $filterTools = '';

    // Validation rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:tools.users,username',
        'password' => 'nullable|string|min:6',
        'role' => 'required',
        'user_limition' => 'nullable|integer|min:0',

    ];


    public $roles = [
        'superadmin' => 'سوپر ادمین',
        'admin' => 'مدیر',
        'warehouse_manager' => 'حسابدار',
        'internal_officer' => 'مدیر مالی',
    ];






    // -------------------------
    // Component initialization
    // -------------------------
    public function mount()
    {

           $this->currentUser = Auth::guard('tools')->user();
        $this->setDefaultValues();
    }

    private function setDefaultValues()
    {
        $currentUser = Auth::guard('tools')->user();

        if ($currentUser->role === 'admin') {
            $this->company_name = $currentUser->company_name;
            $this->address = $currentUser->address;
        } else {
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

    public function updatedPhone($value)
    {
        $this->phone = $this->convertToEnglishNumbers($value);
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
        $this->phone = '';
        $this->user_limition = '';
        $this->modalOpen = false;
        $this->editId = null;
    }

    // -------------------------
    // Open modal for creating user
    // -------------------------
    public function openCreateModal()
    {
        $this->resetInputFields();
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
        $this->company_name = $user->company_name ?? '';
        $this->address = $user->address ?? '';
        $this->phone = $user->phone ?? '';
        $this->user_limition = $user->user_limition ?? null;
        $this->modalOpen = true;
    }

    // -------------------------
    // Search users
    // -------------------------
    public function searchUser()
    {
        $this->resetPage();
    }

    // -------------------------
    // Apply role/Tools filters
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
        $currentUser = Auth::guard('tools')->user();
        $rules = $this->rules;

        if ($this->editId) {
            $rules['username'] = 'required|string|max:255|unique:tools.users,username,' . $this->editId;
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'role' => $this->role,
            'company_name' => $this->company_name,
            'address' => $this->address,
            'phone' => $this->phone,
            'status' => $this->editId ? User::find($this->editId)->status : 0,

        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
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
                'message' => $this->editId ? __('messages.user_updated') : __('messages.user_created')
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
            User::findOrFail($this->confirmDeleteId)->delete();
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

        $html = view('pdf.Tools.user-print', compact('user'))->render();
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
        $currentUser = Auth::guard('tools')->user();
        $query = User::query();

        // Role-based filtering (برای محدود کردن دسترسی کاربر جاری)
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

        if ($this->filterTools) {
            $query->where('company_name', $this->filterTools);
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

    // -------------------------
    // Get unique Tools for filter
    // -------------------------
    public function getToolsProperty()
    {
        return User::select('company_name')->whereNotNull('company_name')->distinct()->pluck('company_name')->toArray();
    }

public function render()
{
    $this->currentUser = Auth::guard('tools')->user();
    return view('livewire.tools-panel.users', [
        'users' => $this->users,
    ]);
}


}
