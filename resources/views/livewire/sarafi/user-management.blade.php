<div class="p-6 space-y-6">

    <h1 class="text-2xl font-bold text-gray-800">مدیریت کاربران</h1>

    {{-- Alert Modal --}}
    @if($alert)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow w-96 text-center">
                <h3 class="text-lg font-bold mb-4">{{ $alert['title'] }}</h3>
                <p class="mb-4">{{ $alert['message'] }}</p>
                <button wire:click="$set('alert', null)" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">باشه</button>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation --}}
    @if($confirmDeleteId)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow w-80 text-center">
                <h3 class="text-lg font-bold mb-4">آیا مطمئن هستید می‌خواهید کاربر را حذف کنید؟</h3>
                <div class="flex justify-around gap-4 mt-4">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">خیر</button>
                    <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">بلی</button>
                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">

        {{-- Form Section --}}
        <div class="lg:w-1/2 bg-white shadow-md rounded p-6">
            <h2 class="text-lg font-semibold mb-4">{{ $editId ? 'ویرایش کاربر' : 'افزودن کاربر' }}</h2>

            <form wire:submit.prevent="save" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input wire:model.defer="name" type="text" placeholder="نام" class="w-full border px-3 py-2 rounded">
                    <input wire:model.defer="lastname" type="text" placeholder="نام خانوادگی" class="w-full border px-3 py-2 rounded">
                    <input wire:model.defer="sarafi_name" type="text" placeholder="نام صرافی" class="w-full border px-3 py-2 rounded">
                    <input wire:model.defer="address" type="text" placeholder="آدرس" class="w-full border px-3 py-2 rounded">
                    <input wire:model.defer="phone" type="text" placeholder="شماره تلفن" class="w-full border px-3 py-2 rounded">
                    <input wire:model.defer="username" type="text" placeholder="نام کاربری" class="w-full border px-3 py-2 rounded">
                    <input wire:model.defer="password" type="password" placeholder="رمز عبور" class="w-full border px-3 py-2 rounded">

                    @if(Auth::check() && Auth::user()->role === 'superadmin')
                        <input wire:model.defer="user_limition" type="number" placeholder="تعداد مجاز کاربران" class="w-full border px-3 py-2 rounded">
                        <select wire:model.defer="role" class="w-full border px-3 py-2 rounded">
                            <option value="">انتخاب نقش</option>
                            <option value="superadmin">سوپرادمین</option>
                            <option value="admin">مدیر</option>
                            <option value="user">کاربر</option>
                        </select>
                    @else
                        <select wire:model.defer="role" class="w-full border px-3 py-2 rounded">
                            <option value="">انتخاب نقش</option>
                            <option value="admin">مدیر</option>
                            <option value="user">کاربر</option>
                        </select>
                    @endif
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" wire:click="$set('modalOpen', false)" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">لغو</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">ذخیره</button>
                </div>
            </form>
        </div>

        {{-- Table Section --}}
        <div class="lg:w-1/2 bg-white shadow-md rounded p-6 overflow-x-auto">
            <div class="flex justify-between items-center mb-4">
                <input type="text" wire:model.debounce.500ms="search" placeholder="جستجو..." class="border rounded px-3 py-2 w-1/2">
                <button wire:click="openCreateModal" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">افزودن کاربر</button>
            </div>

            <table class="min-w-full border text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>نام خانوادگی</th>
                        <th>نام کاربری</th>
                        <th>نقش</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                        <tr class="hover:bg-gray-50 border-b">
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->lastname }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->role }}</td>
                            <td class="flex gap-2">
                                <button wire:click="edit({{ $user->id }})" class="text-blue-600 hover:text-blue-800">ویرایش</button>
                                <button wire:click="confirmDelete({{ $user->id }})" class="text-red-600 hover:text-red-800">حذف</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">{{ $users->links() }}</div>
        </div>
    </div>
</div>

{{-- FontAwesome CDN --}}
@push('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endpush
