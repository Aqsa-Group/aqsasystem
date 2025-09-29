<div class="min-h-screen  dark:bg-gray-900 py-4 w-full p-6">
    <div class="w-full p-4 bg-[#E5E5E5] dark:bg-gray-800 rounded-2xl ">
        <!-- هدر -->
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 vazir dark:text-white tracking-widest">
                {{ $customerId ? __('messages.title_edit') : __('messages.title_add') }}

            </h2>

            <p class="text-lg text-gray-600 dark:text-gray-400 mt-4 vazir">
                {{ __('messages.subtitle') }}
            </p>
        </div>

        <form wire:submit.prevent="saveCustomer" class="w-full">
            <!-- بخش آپلود تصاویر -->
            <div class="flex justify-center gap-72 mb-6">
                <!-- عکس پروفایل -->
                <div class="flex flex-col items-center">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('messages.profile_image') }}

                    </label>
                    <div class="relative w-20 h-20">
                        @if ($newProfile)
                        <img src="{{ $newProfile->temporaryUrl() }}"
                            class="w-20 h-20 rounded-full object-cover border-2 border-blue-400">
                        @elseif($profile && $customerId)
                        <img src="{{ asset('storage/' . $profile) }}"
                            class="w-20 h-20 rounded-full object-cover border-2 border-gray-300">
                        @else
                        <div class="w-20 h-20 rounded-full bg-[#2563EB] flex items-center justify-center">
                            <img src="{{ asset('assets/sarafi/all_icon/profile-circle.svg') }}" alt="">
                        </div>
                        @endif
                        <input type="file" wire:model="newProfile" accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                </div>

                <!-- عکس شناسنامه -->
                <div class="flex flex-col items-center">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('messages.idcard_image') }}

                    </label>
                    <div class="relative w-20 h-20">
                        @if ($newIdCardImage)
                        <img src="{{ $newIdCardImage->temporaryUrl() }}"
                            class="w-20 h-20 rounded-lg object-cover border-2 border-green-400">
                        @elseif($idCardImage && $customerId)
                        <img src="{{ asset('storage/' . $idCardImage) }}"
                            class="w-20 h-20 rounded-lg object-cover border-2 border-gray-300">
                        @else
                        <div class="w-20 h-20 rounded-full bg-[#2563EB] flex items-center justify-center ">
                            <img src="{{ asset('assets/sarafi/all_icon/id.svg') }}" alt="">
                        </div>
                        @endif
                        <input type="file" wire:model="newIdCardImage" accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- فیلدهای اطلاعات -->
            <div class="space-y-4 w-full">
                <!-- ردیف 1 -->
                <div class="grid grid-cols-2 gap-4 w-full">
                    <div class="w-full">
                        <label class="block text-sm font-medium text-black vazir dark:text-gray-300 mb-2">
                            {{ __('messages.fullname') }}
                        </label>
                        <div class="relative w-full">
                            <input type="text" wire:model="fullname"
                                placeholder="{{ __('messages.placeholder_fullname') }} "
                                class="w-full p-3 rounded-xl border py-4 focus:ring-2 focus:border-none focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute left-3 top-4 text-gray-400">
                                <img src="{{ asset('assets/sarafi/all_icon/profile.svg') }}" alt="">
                            </div>
                        </div>
                        @error('fullname') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="w-full">
                        <label class="block text-sm font-medium text-black dark:text-gray-300 mb-2">
                            {{__('messages.account_number') }}
                        </label>
                        <div class="flex gap-2 w-full">
                            <div class="relative flex-1">
                                <input type="text" wire:model.lazy="account"
                                    placeholder="{{ __('messages.placeholder_account') }} "
                                    class="w-full p-3 rounded-xl py-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    maxlength="16" @if(!$customerId) readonly @endif>
                                <div class="absolute left-3 top-4 text-gray-400">
                                    <img src="{{ asset('assets/sarafi/all_icon/card.svg') }}" alt="">

                                </div>
                            </div>
                            @if(!$customerId)
                            <button type="button" wire:click="generateNewAccountNumber"
                                class="px-4 py-3 bg-white  border text-white rounded-lg transition">
                                <img src="{{ asset('assets/sarafi/all_icon/refresh-2.svg') }}" alt="">

                            </button>
                            @endif
                        </div>
                        @error('account') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- ردیف 2 -->
                <div class="grid grid-cols-2 gap-4 w-full">
                    <div class="w-full">
                        <label class="block text-sm font-medium text-black dark:text-gray-300 mb-2 vazir">
                            {{ __('messages.category') }}
                        </label>
                        <div class="relative w-full">
                            <select wire:model="category"
                                class="w-full p-3 rounded-xl py-4  bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                                <option value="">{{ __('messages.choose') }}</option>
                                <option value="normal">{{ __('messages.category_normal') }}</option>
                                <option value="regular">{{ __('messages.category_regular') }}</option>
                                <option value="gold">{{ __('messages.category_gold') }}</option>
                                <option value="special">{{ __('messages.category_special') }}</option>
                            </select>
                            <div class="absolute left-3 top-4 text-gray-400">
                                <img src="{{ asset('assets/sarafi/all_icon/clipboard.svg') }}" alt="">
                            </div>

                        </div>
                        @error('category') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="w-full">
                        <label class="block text-sm font-medium text-black dark:text-gray-300 mb-2 vazir">
                            {{ __('messages.city') }}

                        </label>
                        <div class="relative w-full">
                            <input type="text" wire:model="city" placeholder="{{ __('messages.placeholder_city') }} "
                                class="w-full p-3 rounded-xl py-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute left-3 top-4 text-gray-400">
                                <img src="{{ asset('assets/sarafi/all_icon/Group.svg') }}" alt="">
                            </div>
                        </div>
                        @error('city') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- ردیف 3 -->
                <div class="grid grid-cols-2 gap-4 w-full">
                    <div class="w-full">
                        <label class="block text-sm font-medium text-black vazir dark:text-gray-300 mb-2">
                            {{ __('messages.phone') }}
                        </label>
                        <div class="relative w-full">
                            <input type="text" wire:model.lazy="phone"
                                placeholder="{{ __('messages.placeholder_phone') }} "
                                class="w-full p-3 rounded-xl py-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute left-3 top-4 text-gray-400">
                                <img src="{{ asset('assets/sarafi/all_icon/call.svg') }}" alt="">

                            </div>
                        </div>
                        @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="w-full">
                        <label class="block text-sm font-medium text-black vazir dark:text-gray-300 mb-2">
                            {{ __('messages.tazkira') }}

                        </label>
                        <div class="relative w-full">
                            <input type="text" wire:model.lazy="tazkira"
                                placeholder="{{ __('messages.placeholder_tazkira') }} "
                                class="w-full p-3 rounded-xl py-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute left-3 top-4 text-gray-400">
                                <img src="{{ asset('assets/sarafi/all_icon/qlementine-icons_id-card-16.svg') }}" alt="">
                            </div>
                        </div>
                        @error('tazkira') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- ردیف 4 -->
                <div class="grid grid-cols-2 gap-4 w-full">
                    <div class="w-full">
                        <label class="block text-sm font-medium text-black dark:text-gray-300 mb-2">
                            {{ __('messages.whatsapp') }}
                        </label>
                        <div class="relative w-full">
                            <input type="text" wire:model.lazy="whatsapp"
                                placeholder="{{ __('messages.placeholder_whatsapp') }} "
                                class="w-full p-3 rounded-xl py-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute left-3 top-4 text-green-500">
                                <img src="{{ asset('assets/sarafi/all_icon/Vector.svg') }}" alt="">
                            </div>
                        </div>
                        @error('whatsapp') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="w-full">
                        <label class="block text-sm font-medium text-black dark:text-gray-300 mb-2">
                            {{ __('messages.password') }}
                        </label>
                        <div class="relative w-full">
                            <input type="password" wire:model="password"
                                placeholder="{{ __('messages.placeholder_password') }} "
                                class="w-full p-3 rounded-xl py-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute left-3 top-4 text-gray-400">
                                <img src="{{ asset('assets/sarafi/all_icon/lock.svg') }}" alt="">
                            </div>
                        </div>
                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- دکمه‌های اقدام -->
            <div class="flex justify-center gap-4 mt-8 pt-6 pb-5 border-t border-[#8C8C8C] dark:border-gray-700 w-full">
                <!-- لغو -->
                <button type="button" wire:click="resetForm"
                    class="flex items-center justify-center gap-2 w-1/2 py-4 text-sm bg-[#B10909] text-white rounded-xl dark:bg-gray-700 dark:text-gray-200 transition">
                    {{ __('messages.cancel') }}

                </button>

                <!-- ذخیره / بروزرسانی -->
                <button type="submit"
                    class="flex items-center justify-center gap-2 w-1/2 py-4 text-sm bg-[#2563EB] text-white rounded-xl hover:bg-blue-700 transition">
                    {{ $customerId ? __('messages.update') : __('messages.save') }}
                </button>
            </div>

        </form>
    </div>

    <!-- مودال موفقیت -->
    @if($showSuccessModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
        wire:click.self="$set('showSuccessModal', false)">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-96">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-check text-2xl text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <h2 class="text-xl font-bold text-green-600 text-center">{{ __('messages.success_title') }}</h2>
            <p class="text-gray-700 dark:text-gray-200 text-center mt-2 mb-4">
                {{ $successMessage }}
            </p>
            <div class="flex justify-center">
                <button wire:click="closeSuccessModal"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    {{ __('messages.close') }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>