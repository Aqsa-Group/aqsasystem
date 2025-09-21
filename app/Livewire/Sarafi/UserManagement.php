<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;

class UserManagement extends Component
{
    use WithPagination;

    // Search, modal and edit state
    public $search = '';
    public $modalOpen = false;
    public $editId = null;
    public $filterOpen = false;


    // Form fields
    public $name, $lastname, $username, $password, $role, $sarafi_name, $address, $phone, $user_limition;

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
        //
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
        $this->sarafi_name = '';
        $this->address = '';
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
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'role' => $this->role,
            'sarafi_name' => $this->sarafi_name,
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
            $this->alert = ['title' => 'Success', 'message' => 'User updated.'];
        } else {
            User::create($data);
            $this->alert = ['title' => 'Success', 'message' => 'User created.'];
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

    // -------------------------
    // Delete user
    // -------------------------
    public function delete()
    {
        if ($this->confirmDeleteId) {
            User::findOrFail($this->confirmDeleteId)->delete();
            $this->alert = ['title' => 'Success', 'message' => 'User deleted.'];
            $this->confirmDeleteId = null;
        }
    }

    // -------------------------
    // Get paginated users
    // -------------------------
   public function getUsersProperty()
{
    $currentUser = Auth::guard('sarafi')->user();
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
        $query->where(function($q) {
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

    // -------------------------
    // Get unique sarafis for filter
    // -------------------------
    public function getSarafisProperty()
    {
        return User::select('sarafi_name')->whereNotNull('sarafi_name')->distinct()->pluck('sarafi_name')->toArray();
    }

    // -------------------------
    // Render view
    // -------------------------
    public function render()
    {
        $currentUser = Auth::guard('sarafi')->user();
        $users = $this->users;

        return view('livewire.sarafi.user-management', compact('users', 'currentUser'));
    }
}
