<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $modalOpen = false;
    public $editId = null;

    public $name, $lastname, $username, $password, $role, $sarafi_name, $address, $phone, $user_limition;
    public $alert = null;
    public $confirmDeleteId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:sarafi.users,username',
        'password' => 'nullable|string|min:6',
        'role' => 'required|in:superadmin,admin,user',
        'user_limition' => 'nullable|integer|min:0',
    ];

    public function render()
    {
        $query = User::query()
            ->where(function($q){
                $q->where('name','like','%'.$this->search.'%')
                  ->orWhere('lastname','like','%'.$this->search.'%')
                  ->orWhere('username','like','%'.$this->search.'%');
            });

        // Admin فقط کاربران خودش را ببیند
        if(Auth::check() && Auth::user()->role === 'admin') {
            $query->where('admin_id', Auth::id());
        }

        $users = $query->paginate(10);

        return view('livewire.sarafi.user-management', compact('users'));
    }

    public function openCreateModal()
    {
        $this->resetInputFields();
        $this->editId = null;
        $this->modalOpen = true;
    }

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

    public function save()
    {
        $rules = $this->rules;
        if($this->editId){
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
            'status' => 1,
        ];

        if($this->password){
            $data['password'] = bcrypt($this->password);
        }

        // سوپرادمین
        if(Auth::check() && Auth::user()->role === 'superadmin' && !$this->editId){
            if($this->role === 'admin'){
                $data['user_limition'] = $this->user_limition ?? 0;
                $data['admin_id'] = null;
            } else {
                $data['admin_id'] = null;
            }
        }

        // admin
        if(Auth::check() && Auth::user()->role === 'admin' && !$this->editId){
            $userCount = User::where('admin_id', Auth::id())->count();
            if($userCount >= (Auth::user()->user_limition ?? 0)){
                $this->alert = ['title'=>'خطا','message'=>'به حداکثر تعداد کاربران خود رسیدید.'];
                return;
            }
            $data['admin_id'] = Auth::id();
            $data['user_limition'] = 0;
        }

        if($this->editId){
            User::find($this->editId)->update($data);
            $this->alert = ['title'=>'موفقیت','message'=>'کاربر ویرایش شد.'];
        } else {
            User::create($data);
            $this->alert = ['title'=>'موفقیت','message'=>'کاربر ایجاد شد.'];
        }

        $this->modalOpen = false;
        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if($this->confirmDeleteId){
            User::findOrFail($this->confirmDeleteId)->delete();
            $this->alert = ['title'=>'موفقیت','message'=>'کاربر حذف شد.'];
            $this->confirmDeleteId = null;
        }
    }

    private function resetInputFields()
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
    }
}
