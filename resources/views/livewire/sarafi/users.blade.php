<div>
    <!-- فرم ثبت کاربر -->
    <div class="w-[1360px] p-4 bg-[#F5F5F5] dark:bg-gray-800 rounded-2xl mx-auto space-y-2"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

        <!-- عنوان و آیکون -->
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-bold text-gray-900 vazir dark:text-white tracking-widest">
                {{ __('messages.title_add_user') }}
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 vazir">
                {{ __('messages.subtitle_user') }}
            </p>
            <div class="bg-[#2563EB] rounded-full h-20 w-20 mx-auto flex items-center justify-center">
                <img src="{{ asset('assets/sarafi/all_icon/light.user.svg') }}" alt="" class="mt-2">
            </div>
        </div>

        @php
        $currentUser = Auth::guard('sarafi')->user();
        @endphp

        <!-- فرم اطلاعات -->
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-2 gap-3">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-black vazir dark:text-gray-300 mb-1">
                        {{ __('messages.name') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="name" placeholder="{{ __('messages.placeholder_name') }}" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="{{ asset('assets/sarafi/all_icon/profile.svg') }}" alt="">
                        </div>
                    </div>
                    @error('name')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Lastname -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        {{ __('messages.lastname') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="lastname" placeholder="{{ __('messages.placeholder_lastname') }}"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="{{ asset('assets/sarafi/all_icon/profile.svg') }}" alt="">
                        </div>
                    </div>
                    @error('lastname')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Sarafi Name -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        {{ __('messages.sarafi_name') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="sarafi_name"
                            placeholder="{{ __('messages.placeholder_sarafi_name') }}" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="{{ asset('assets/sarafi/all_icon/buildings-2.svg') }}" alt="">
                        </div>
                    </div>
                    @error('sarafi_name')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        {{ __('messages.address') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="address" placeholder="{{ __('messages.placeholder_address') }}"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="{{ asset('assets/sarafi/all_icon/location.svg') }}" alt="">
                        </div>
                    </div>
                    @error('address')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        {{ __('messages.phone_user') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="phone" placeholder="{{ __('messages.placeholder_phone_user') }}"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="{{ asset('assets/sarafi/all_icon/call.svg') }}" alt="">
                        </div>
                    </div>
                    @error('phone')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1 vazir">
                        {{ __('messages.username') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="username" placeholder="{{ __('messages.placeholder_username') }}"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="{{ asset('assets/sarafi/all_icon/profile.svg') }}" alt="">
                        </div>
                    </div>
                    @error('username')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        {{ __('messages.password') }}
                    </label>
                    <div class="relative">
                        <input type="password" wire:model="password"
                            placeholder="{{ __('messages.placeholder_userpassword') }}" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="{{ asset('assets/sarafi/all_icon/lock.svg') }}" alt="">
                        </div>
                    </div>
                    @error('password')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Role -->
                @if ($currentUser && $currentUser->role === 'superadmin')
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1 vazir">
                        {{ __('messages.category_user') }}
                    </label>
                    <div class="relative">
                        <select wire:model="role"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                           focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                            <option value="">{{ __('messages.select_role') }}</option>
                            <option value="admin">{{ __('messages.admin') }}</option>

                        </select>
                        <div class="absolute left-2 top-2">
                            <img src="{{ asset('assets/sarafi/all_icon/clipboard.svg') }}" alt="">
                        </div>
                    </div>
                    @error('role')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                @elseif ($currentUser && $currentUser->role === 'admin')
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1 vazir">
                        {{ __('messages.category_user') }}
                    </label>
                    <div class="relative">
                        <select wire:model="role"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                           focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                            <option value="">{{ __('messages.choose_user') }}</option>
                            <option value="">{{ __('messages.select_role') }}</option>
                            <option value="warehouse_manager">{{ __('messages.warehouse_manager') }}</option>
                            <option value="internal_officer">{{ __('messages.internal_officer') }}</option>
                            <option value="external_officer">{{ __('messages.external_officer') }}</option>
                        </select>
                        <div class="absolute left-2 top-2">
                            <img src="{{ asset('assets/sarafi/all_icon/clipboard.svg') }}" alt="">
                        </div>
                    </div>
                    @error('role')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                <!-- User Limition -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1 vazir">
                        {{ __('messages.user_limit') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="user_limition"
                            placeholder="{{ __('messages.placeholder_user_limit') }}" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="{{ asset('assets/sarafi/all_icon/customers.svg') }}" alt="" class="h-8 w-8">
                        </div>
                    </div>
                    @error('user_limition')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <!-- دکمه‌ها -->
            <div class="flex justify-center gap-4 mt-3 pt-2">
                <button type="button" wire:click="resetForm"
                    class="flex-1 py-4 bg-[#B10909] text-white rounded-xl hover:bg-gray-700 transition">
                    {{ __('messages.cancel') }}
                </button>
                <button type="submit"
                    class="flex-1 py-4 bg-[#2563EB] text-white rounded-xl hover:bg-blue-700 transition">
                    {{ __('messages.save') }}
                </button>
            </div>
        </form>
    </div>

    <!-- فیلتر و سرچ -->
    <div class="flex items-center mt-5 gap-3 w-[1360px] mx-auto">

        <!-- دکمه فیلتر -->
        <div class="relative">
            <button wire:click="$toggle('filterOpen')"
                class="px-3 py-2 border rounded-lg bg-[#2563EB] transition flex items-center gap-2 text-white">
                <img src="{{ asset('assets/sarafi/all_icon/filter.svg') }}" alt="">
                <span class="text-white">{{ __('messages.filter') }}</span>
            </button>

            @if ($filterOpen)
            <div class="absolute top-full mt-2 bg-white border rounded-xl shadow-lg p-4 w-72 z-50 flex flex-col gap-3">
                <select wire:model="filterRole" class="border rounded px-3 py-2 w-full">
                    <option value="">{{ __('messages.all_roles') }}</option>
                    @foreach ($roles as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>

                <select wire:model="filterSarafi" class="border rounded px-3 py-2 w-full">
                    <option value="">{{ __('messages.all_sarafis') }}</option>
                    @foreach ($this->sarafis as $sarafi)
                    <option value="{{ $sarafi }}">{{ $sarafi }}</option>
                    @endforeach
                </select>

                <button wire:click="applyFilter"
                    class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 w-full">
                    {{ __('messages.apply_filter') }}
                </button>
            </div>
            @endif
        </div>

        <!-- جستجو -->
        <div class="relative w-96">
            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5">
            <input type="text" wire:model.debounce.500ms="search" wire:keydown.enter="searchUser"
                placeholder="{{ __('messages.search_placeholder') }}"
                class="w-full border border-gray-300 rounded-2xl pl-10 pr-3 py-3 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm">
        </div>
    </div>

    <!-- جدول کاربران -->
    <div class="w-[1360px] mt-4 mx-auto relative overflow-x-auto shadow-md sm:rounded-lg bg-[#F5F5F5] dark:bg-gray-900"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <table class="min-w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-5">

            <!-- هدر جدول -->
            <thead class="bg-[#2563EB] dark:bg-gray-700 text-white text-[18px] vazir h-20">
                <tr>
                    <th class="px-6 py-6 font-bold">
                        <span class="border border-white h-2 w-5 px-3 rounded-lg">#</span>
                    </th>
                    <th class="px-6 py-6 font-bold">{{ __('messages.fullname') }}</th>
                    <th class="px-6 py-6 font-bold">{{ __('messages.sarafi_name') }}</th>
                    <th class="px-6 py-6 font-bold">{{ __('messages.username') }}</th>
                    <th class="px-6 py-6 font-bold">{{ __('messages.category_user') }}</th>
                    <th class="px-6 py-6 font-bold">{{ __('messages.status') }}</th>
                    <th class="px-6 py-6 font-bold text-center">{{ __('messages.actions') }}</th>
                </tr>
            </thead>


            <!-- بدنه جدول -->
            <tbody>
                @forelse ($users as $index => $user)
                <tr class="border-b dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-3 py-2 vazir text-[16px] font-medium ">{{ $users->firstItem() + $index }}</td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir">{{ $user->name }}</td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir">{{ $user->sarafi_name }}</td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir">{{ $user->username }}</td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir">{{ $roles[$user->role] ?? $user->role }}</td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir">
                        @if ($user->status)
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">
                            {{ __('messages.active') }}
                        </span>
                        @else
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">
                            {{ __('messages.inactive') }}
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex justify-center gap-2">
                        <button wire:click="edit({{ $user->id }})" class="px-2 py-1">
                            <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}" class="w-6 h-6" alt="Edit">
                        </button>
                        <button wire:click="confirmDelete({{ $user->id }})" class="px-2 py-1">
                            <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}" class="w-6 h-6"
                                alt="Delete">
                        </button>
                        <button class="px-2 py-1">
                            <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}" class="w-8 h-8"
                                alt="Print">
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        هیچ مشتری یافت نشد.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- هشدار --}}
    @if ($alert)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-3xl shadow-xl w-[600px] text-center animate-fadeIn z-50">
            <h3 class="text-2xl font-bold mb-3 text-green-800">{{ $alert['title'] }}</h3>
            <p class="text-gray-600 mb-4 text-2xl">{{ $alert['message'] }}</p>
            <button wire:click="$set('alert', null)"
                class="px-56 py-4 bg-[#2563EB] text-white rounded-xl hover:bg-blue-700 transition">
                {{ __('messages.ok') }}
            </button>
        </div>
    </div>
    @endif

    {{-- تایید حذف --}}
    @if ($confirmDeleteId)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-3xl shadow-xl w-[600px] text-center animate-fadeIn z-50">
            <h3 class="text-xl font-bold mb-4 text-red-600 vazir">{{ __('messages.confirm_delete_title') }}</h3>
            <p class="text-gray-600 mb-6 text-2xl vazir">{{ __('messages.confirm_delete_message') }}</p>
            <div class="flex justify-center gap-4">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-28 py-4 bg-gray-300 rounded-xl hover:bg-gray-400 transition">{{ __('messages.no')
                    }}</button>
                <button wire:click="delete"
                    class="px-28 py-4 bg-red-600 text-white rounded-xl hover:bg-red-700 transition flex items-center gap-2">
                    <i class="fas fa-trash-alt"></i> {{ __('messages.yes') }}
                </button>
            </div>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endpush
</div>