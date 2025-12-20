<div>

    <div>
        {{-- Livewire alert (موفقیت/خطا) --}}
        @if ($alert)
        <div x-data="{
            show: true,
            init() {
                // مشاهده تغییرات $alert در Livewire
                $wire.watch('alert', (value) => {
                    if (value) {
                        this.show = true;
                        setTimeout(() => {
                            this.show = false;
                            // پاک کردن alert بعد از ۴ ثانیه
                            setTimeout(() => $wire.clearAlert(), 300);
                        }, 4000);
                    }
                });
                
                // تایمر برای هشدار فعلی
                setTimeout(() => {
                    this.show = false;
                    setTimeout(() => $wire.clearAlert(), 300);
                }, 4000);
            }
        }" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] {{ $alert['title'] === 'Error' ? 'bg-red-500' : 'bg-[#2B65E5]' }} vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    {{ $alert['message'] }}
                </h2>
            </div>
        </div>
        @endif

        {{-- پیام Session --}}
        @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2B65E5] vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    {{ session('message') }}
                </h2>
            </div>
        </div>
        @endif
    </div>



    @if($currentUser && $currentUser->role === 'admin' || $currentUser->role === 'superadmin' )
     <div class="pl-5">
           <!-- فرم ثبت کاربر -->
    <div class="w-[460px] md-w-[100px] lg:w-[1200px] p-4 mx-auto bg-[#F5F5F5] dark:bg-black dark:border dark:border-white rounded-2xl mx-auto space-y-2"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

        <!-- عنوان و آیکون -->
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-bold text-gray-900 vazir dark:text-white tracking-widest">
                {{ __('messages.title_add_user') }}
            </h2>
            <p class="text-lg text-gray-600 dark:text-white vazir">
                {{ __('messages.subtitle_user') }}
            </p>
            <div class="bg-[#545964] rounded-full h-20 w-20 mx-auto flex items-center justify-center">
<img
    src="{{ asset('assets/sarafi/all_icon/light.user.svg') }}"
    alt=""
    class="mt-2 dark:invert"
/>
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
                    <label class="block text-sm font-medium text-black dark:text-white vazir mb-1">
                        {{ __('messages.name') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="name" placeholder="{{ __('messages.placeholder_name') }}" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                         focus:ring-blue-500 dark:bg-black dark:placeholder:text-white dark:border-white dark:text-white">
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
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        {{ __('messages.lastname') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="lastname" placeholder="{{ __('messages.placeholder_lastname') }}"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="{{ asset('assets/sarafi/all_icon/profile.svg') }}" alt="">
                        </div>
                    </div>
                    @error('lastname')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Sarafi Name -->
                <!-- در فایل Blade -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        {{ __('messages.sarafi_name') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="sarafi_name"
                            placeholder="{{ __('messages.placeholder_sarafi_name') }}" {{
                            Auth::guard('sarafi')->user()->role === 'admin' ? : '' }}
                        class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C]
                        focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white
                        {{ Auth::guard('sarafi')->user()->role === 'admin' ? 'bg-gray-100' : '' }}">
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
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        {{ __('messages.address') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="address" placeholder="{{ __('messages.placeholder_address') }}"
                            {{ Auth::guard('sarafi')->user()->role === 'admin' ? : '' }}
                        class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C]
                        focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white
                        {{ Auth::guard('sarafi')->user()->role === 'admin' ? 'bg-gray-100' : '' }}">
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
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        {{ __('messages.phone_user') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model.lazy="phone"
                            placeholder="{{ __('messages.placeholder_phone_user') }}" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">
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
                    <label class="block text-sm font-medium text-black dark:text-white mb-1 vazir">
                        {{ __('messages.username') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="username" placeholder="{{ __('messages.placeholder_username') }}"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">
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
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        {{ __('messages.password') }}
                    </label>
                    <div class="relative">
                        <input type="password" wire:model.lazy="password"
                            placeholder="{{ __('messages.placeholder_userpassword') }}" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">
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
                    <label class="block text-sm font-medium text-black dark:text-white mb-1 vazir">
                        {{ __('messages.category_user') }}
                    </label>
                    <div class="relative">
                        <select wire:model="role"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                           focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white appearance-none">
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
                    <label class="block text-sm font-medium text-black dark:text-white mb-1 vazir">
                        {{ __('messages.category_user') }}
                    </label>
                    <div class="relative">
                        <select wire:model="role"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                           focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white appearance-none">
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
                @if ($currentUser && $currentUser->role === 'superadmin')
                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-1 vazir">
                        {{ __('messages.user_limit') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="user_limition"
                            placeholder="{{ __('messages.placeholder_user_limit') }}" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="{{ asset('assets/sarafi/all_icon/customers.svg') }}" alt="" class="h-8 w-8">
                        </div>
                    </div>
                    @error('user_limition')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                @endif



                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-1 vazir">
                        {{ __('messages.zone') }}
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="zone" placeholder="{{ __('messages.placeholder_zone') }}" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="{{ asset('assets/sarafi/all_icon/location.svg') }}" alt="">
                        </div>
                    </div>
                    @error('zone')
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
     </div>

    @endif

    <!-- فیلتر و سرچ -->
    <div class="flex w-[460px] md-w-[800px] lg:w-[1200px] items-center mt-5 gap-3 mx-auto">

        <!-- دکمه فیلتر -->
        <div class="relative">
            <button wire:click="$toggle('filterOpen')"
                class="px-3 py-2 border rounded-lg dark:bg-black bg-[#2563EB] transition flex items-center gap-2 text-white">
                <img src="{{ asset('assets/sarafi/all_icon/filter.svg') }}" alt="">
                <span class="text-white">{{ __('messages.filter') }}</span>
            </button>

            @if ($filterOpen)
            <div class="absolute top-full mt-2 dark:bg-black bg-white border rounded-xl shadow-lg p-4 w-72 z-50 flex flex-col gap-3">
                <select wire:model="filterRole" class="border rounded px-3 py-2 w-full dark:bg-black dark:text-white">
                    <option value="">{{ __('messages.all_roles') }}</option>
                    @foreach ($roles as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>

                <select wire:model="filterSarafi" class="border rounded px-3 py-2 w-full dark:bg-black dark:text-white">
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
        <div class="relative w-80">
            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 dark:hidden">
                <svg width="24"   class=" hidden dark:block absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M22 22L20 20" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

            <input type="text" wire:model.debounce.500ms="search" wire:keydown.enter="searchUser"
                placeholder="{{ __('messages.search_placeholder') }}"
                class="w-full dark:placeholder:text-white dark:bg-black dark:border-white border border-gray-300 rounded-2xl pl-10 pr-3 py-3 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm">
        </div>
    </div>

    <!-- جدول کاربران -->
    <div class="w-[420px] md-w-[1700px] lg:w-[1200px] p-6 mt-4  relative overflow-x-auto shadow-md sm:rounded-lg mb-4 mx-auto dark:bg-black dark:border dark:border-white  bg-[#F5F5F5]"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <table class="min-w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-5">

            <!-- هدر جدول -->
            <thead class="bg-[#2563EB] dark:bg-gray-700 text-white w-full text-[18px] vazir h-20">
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
                <tr class="border-b dark:bg-black dark:text-white hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-3 py-2 vazir text-[16px] font-medium dark:text-white">{{ $users->firstItem() + $index }}</td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium dark:text-white text-black vazir">{{ $user->name }}</td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium dark:text-white text-black vazir">{{ $user->sarafi_name }}</td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium dark:text-white text-black vazir">{{ $user->username }}</td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium dark:text-white text-black vazir">{{ $roles[$user->role] ??
                        $user->role }}</td>
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
  <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                    class="w-7 h-7 dark:hidden" alt="Edit">

                                                <svg width="22" height="22" class="hidden dark:block"
                                                    viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M10.082 1.83325H8.2487C3.66536 1.83325 1.83203 3.66659 1.83203 8.24992V13.7499C1.83203 18.3333 3.66536 20.1666 8.2487 20.1666H13.7487C18.332 20.1666 20.1654 18.3333 20.1654 13.7499V11.9166"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M14.7027 2.76832L7.4794 9.99165C7.2044 10.2667 6.9294 10.8075 6.8744 11.2017L6.48023 13.9608C6.33357 14.96 7.0394 15.6567 8.03857 15.5192L10.7977 15.125C11.1827 15.07 11.7236 14.795 12.0077 14.52L19.2311 7.29665C20.4777 6.04999 21.0644 4.60165 19.2311 2.76832C17.3977 0.934987 15.9494 1.52165 14.7027 2.76832Z"
                                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path
                                                        d="M13.668 3.8042C14.2821 5.99503 15.9963 7.7092 18.1963 8.33253"
                                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>                        </button>
                        <button wire:click="confirmDelete({{ $user->id }})" class="px-2 py-1">
                           <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-8 h-8 dark:hidden" alt="Delete">
                                                <svg width="24" height="24" class="hidden dark:block"
                                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M21 5.97998C17.67 5.64998 14.32 5.47998 10.98 5.47998C9 5.47998 7.02 5.57998 5.04 5.77998L3 5.97998"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M8.5 4.97L8.72 3.66C8.88 2.71 9 2 10.69 2H13.31C15 2 15.13 2.75 15.28 3.67L15.5 4.97"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M18.8484 9.13989L18.1984 19.2099C18.0884 20.7799 17.9984 21.9999 15.2084 21.9999H8.78844C5.99844 21.9999 5.90844 20.7799 5.79844 19.2099L5.14844 9.13989"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M10.3281 16.5H13.6581" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.5 12.5H14.5" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>

                        </button>
                        <button class="px-2 py-1" wire:click="print({{ $user->id }})">
                              <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-10 h-10 dark:hidden" alt="Print">
                                                <svg width="30" class="hidden dark:block" height="30"
                                                    viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M10.7714 25.0001C10.2156 25.0001 9.74016 24.8022 9.34516 24.4063C8.95016 24.0105 8.75224 23.5359 8.75141 22.9826V20.0001H6.49141C5.93641 20.0001 5.46141 19.8022 5.06641 19.4063C4.67141 19.0105 4.47349 18.5355 4.47266 17.9813V13.2688C4.47266 12.5605 4.71307 11.9672 5.19391 11.4888C5.67474 11.0088 6.26766 10.7688 6.97266 10.7688H23.0302C23.7385 10.7688 24.3322 11.0088 24.8114 11.4888C25.2906 11.9688 25.5302 12.5622 25.5302 13.2688V17.9813C25.5302 18.5363 25.3327 19.0113 24.9377 19.4063C24.5427 19.8013 24.0672 19.9992 23.5114 20.0001H21.2514V22.9813C21.2514 23.5363 21.0535 24.0113 20.6577 24.4063C20.2618 24.8013 19.7868 24.9992 19.2327 25.0001H10.7714ZM6.49141 18.7501H8.75141C8.78391 18.2226 8.99307 17.7701 9.37891 17.3926C9.76474 17.0159 10.2289 16.8276 10.7714 16.8276H19.2327C19.7743 16.8276 20.2381 17.0163 20.6239 17.3938C21.0097 17.7705 21.2189 18.2226 21.2514 18.7501H23.5114C23.7356 18.7501 23.9197 18.678 24.0639 18.5338C24.2081 18.3897 24.2802 18.2055 24.2802 17.9813V13.2688C24.2802 12.9155 24.1606 12.6188 23.9214 12.3788C23.6822 12.1388 23.3852 12.0188 23.0302 12.0188H6.97266C6.61849 12.0188 6.32182 12.1388 6.08266 12.3788C5.84349 12.6188 5.72349 12.9159 5.72266 13.2701V17.9813C5.72266 18.2055 5.79474 18.3897 5.93891 18.5338C6.08307 18.678 6.26724 18.7501 6.49141 18.7501ZM20.0014 10.7701V7.78758C20.0014 7.56258 19.9293 7.37841 19.7852 7.23508C19.641 7.09091 19.4568 7.01883 19.2327 7.01883H10.7702C10.546 7.01883 10.3618 7.09091 10.2177 7.23508C10.0735 7.37925 10.0014 7.56341 10.0014 7.78758V10.7688H8.75141V7.78758C8.75141 7.23258 8.94932 6.75716 9.34516 6.36133C9.74016 5.9655 10.2152 5.76758 10.7702 5.76758H19.2327C19.7877 5.76758 20.2627 5.9655 20.6577 6.36133C21.0535 6.75716 21.2514 7.23216 21.2514 7.78633V10.7688L20.0014 10.7701ZM22.0214 15.1451C22.3756 15.1451 22.6722 15.0251 22.9114 14.7851C23.1506 14.5451 23.2706 14.2484 23.2714 13.8951C23.2722 13.5417 23.1522 13.2447 22.9114 13.0038C22.6706 12.763 22.3739 12.643 22.0214 12.6438C21.6689 12.6447 21.3718 12.7647 21.1302 13.0038C20.8885 13.243 20.7689 13.5401 20.7714 13.8951C20.7739 14.2501 20.8935 14.5467 21.1302 14.7851C21.3668 15.0234 21.6639 15.1434 22.0214 15.1451ZM20.0014 22.9801V18.8463C20.0014 18.6213 19.9293 18.4367 19.7852 18.2926C19.641 18.1484 19.4568 18.0763 19.2327 18.0763H10.7702C10.546 18.0763 10.3618 18.1484 10.2177 18.2926C10.0735 18.4376 10.0014 18.6222 10.0014 18.8463V22.9813C10.0014 23.2055 10.0735 23.3897 10.2177 23.5338C10.3618 23.678 10.5464 23.7501 10.7714 23.7501H19.2327C19.4568 23.7501 19.641 23.678 19.7852 23.5338C19.9293 23.3897 20.0014 23.2051 20.0014 22.9801ZM6.49141 12.0201H5.72266H24.2802H6.49141Z"
                                                        fill="white" />
                                                </svg>
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



    @if($currentUser && $currentUser->role === 'admin')

    {{-- مودال تأیید حذف --}}
    @if ($confirmDeleteId)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div
            class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] rounded-[12px] shadow-xl w-[653px] h-[219.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">

            <!-- دکمه بستن -->
            <button wire:click="$set('confirmDeleteId', null)"
                class="absolute left-0 right-4 top-4 h-6 w-6 flex items-center justify-center">
                <img src="{{ asset('assets/sarafi/all_icon/close.svg') }}" alt="بستن" class="w-4 h-4">
            </button>

            <!-- عنوان -->
            <h1 class="text-2xl text-black shabnam font-medium leading-[100%] mt-2">
                {{ __('messages.confirm_delete_title') }}
            </h1>

            <!-- خط جداکننده -->
            <hr class="bg-[#E1DED3] mt-4 mx-4">

            <!-- پیام تأیید -->
            <p class="mb-6 text-xl shabnam mt-5">
                {{ __('messages.confirm_delete_message') }}
            </p>

            <!-- دکمه‌ها -->
            <div class="flex justify-center gap-4">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-12 text-white text-lg shabnam-fd py-3 bg-[#DD2424] rounded-xl transition hover:bg-red-700">
                    {{ __('messages.no') }}
                </button>
                <button wire:click="delete"
                    class="px-12 py-3 bg-[#2563EB] text-lg shabnam-fd text-white rounded-xl transition hover:bg-blue-700 flex items-center gap-2">
                    {{ __('messages.yes') }}
                </button>
            </div>
        </div>
    </div>
    @endif

    @endif


</div>

@push('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endpush
</div>