<div class=" h-fit bg-gray-50 dark:bg-gray-900 py-4">
    <div class="w-full max-ful mx-auto h-full  p-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <!-- هدر -->
        <div class="text-center mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ $customerId ? 'ویرایش مشتری' : 'افزودن مشتری جدید' }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                لطفا اطلاعات مشتری را با دقت وارد نمایید
            </p>
        </div>

        <form wire:submit.prevent="saveCustomer">
            <!-- بخش آپلود تصاویر - فشرده -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <!-- عکس پروفایل -->
                <div class="flex flex-col items-center">
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">عکس پروفایل</label>
                    <div class="relative w-16 h-16">
                        @if ($newProfile)
                            <img src="{{ $newProfile->temporaryUrl() }}" 
                                 class="w-16 h-16 rounded-full object-cover border-2 border-blue-400">
                        @elseif($profile && $customerId)
                            <img src="{{ asset('storage/' . $profile) }}" 
                                 class="w-16 h-16 rounded-full object-cover border border-gray-300">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                                <i class="fa-solid fa-user text-gray-400 text-lg"></i>
                            </div>
                        @endif
                        <input type="file" wire:model="newProfile" accept="image/*"
                               class="absolute inset-0 opacity-0 cursor-pointer text-xs">
                    </div>
                    @if($newProfile)
                        <div class="text-xs text-gray-500 mt-1 text-center truncate w-full">
                            {{ Str::limit($newProfile->getClientOriginalName(), 10) }}
                        </div>
                    @endif
                </div>

                <!-- عکس شناسنامه -->
                <div class="flex flex-col items-center">
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">عکس شناسنامه</label>
                    <div class="relative w-16 h-16">
                        @if ($newIdCardImage)
                            <img src="{{ $newIdCardImage->temporaryUrl() }}" 
                                 class="w-16 h-16 rounded-lg object-cover border-2 border-green-400">
                        @elseif($idCardImage && $customerId)
                            <img src="{{ asset('storage/' . $idCardImage) }}" 
                                 class="w-16 h-16 rounded-lg object-cover border border-gray-300">
                        @else
                            <div class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                <i class="fa-solid fa-id-card text-gray-400 text-lg"></i>
                            </div>
                        @endif
                        <input type="file" wire:model="newIdCardImage" accept="image/*"
                               class="absolute inset-0 opacity-0 cursor-pointer text-xs">
                    </div>
                    @if($newIdCardImage)
                        <div class="text-xs text-gray-500 mt-1 text-center truncate w-full">
                            {{ Str::limit($newIdCardImage->getClientOriginalName(), 10) }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- فیلدهای اطلاعات - طراحی فشرده -->
            <div class="space-y-3">
                <!-- ردیف 1 -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">نام کامل</label>
                        <div class="relative">
                            <input type="text" wire:model="fullname" placeholder="نام مشتری"
                                   class="w-full text-sm p-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute left-2 top-2 text-gray-400">
                                <i class="fa-solid fa-user text-xs"></i>
                            </div>
                        </div>
                        @error('fullname') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                  <div>
    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">شماره حساب</label>
    <div class="flex gap-2">
        <div class="relative flex-1">
            <input type="text" wire:model.lazy="account" placeholder="شماره حساب ۱۶ رقمی"
                   class="w-full text-sm p-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                   maxlength="16"
                   @if(!$customerId) readonly @endif>
            <div class="absolute left-2 top-2 text-gray-400">
                <i class="fa-solid fa-credit-card text-xs"></i>
            </div>
        </div>
        @if(!$customerId)
        <button type="button" wire:click="generateNewAccountNumber" 
                class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs">
            <i class="fa-solid fa-refresh"></i>
        </button>
        @endif
    </div>
    @error('account') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    @if(!$customerId)
    <p class="text-xs text-gray-500 mt-1">شماره حساب به صورت خودکار تولید می‌شود</p>
    @endif
</div>
                </div>

                <!-- ردیف 2 -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">دسته بندی</label>
                        <div class="relative">
                            <select wire:model="category" class="w-full text-sm p-2 rounded border border-gray-300 bg-white focus:ring-1 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                                <option value="">انتخاب کنید</option>
                                <option value="مشتری عادی">مشتری عادی</option>
                                <option value="مشتریان ثابت">مشتریان ثابت</option>
                                <option value="مشتری طلایی">مشتری طلایی</option>
                                <option value="ویژه">ویژه</option>
                            </select>
                            <div class="absolute left-2 top-2 text-gray-400">
                                <i class="fa-solid fa-tags text-xs"></i>
                            </div>
                        </div>
                        @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">شهر</label>
                        <div class="relative">
                            <input type="text" wire:model="city" placeholder="نام شهر"
                                   class="w-full text-sm p-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute left-2 top-2 text-gray-400">
                                <i class="fa-solid fa-city text-xs"></i>
                            </div>
                        </div>
                        @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- ردیف 3 -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">شماره تلفن</label>
                        <div class="relative">
                            <input type="text" wire:model.lazy="phone" placeholder="07XX-XXX-XXXX"
                                   class="w-full text-sm p-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute left-2 top-2 text-gray-400">
                                <i class="fa-solid fa-phone text-xs"></i>
                            </div>
                        </div>
                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">شماره تذکره</label>
                        <div class="relative">
                            <input type="text" wire:model.lazy="tazkira" placeholder="شماره تذکره"
                                   class="w-full text-sm p-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute left-2 top-2 text-gray-400">
                                <i class="fa-solid fa-id-card-clip text-xs"></i>
                            </div>
                        </div>
                        @error('tazkira') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- ردیف 4 -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">شماره واتساپ</label>
                        <div class="relative">
                            <input type="text" wire:model.lazy="whatsapp" placeholder="07XX-XXX-XXXX"
                                   class="w-full text-sm p-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute left-2 top-2 text-green-500">
                                <i class="fa-brands fa-whatsapp text-xs"></i>
                            </div>
                        </div>
                        @error('whatsapp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">رمز عبور</label>
                        <div class="relative">
                            <input type="password" wire:model="password" placeholder="رمز عبور"
                                   class="w-full text-sm p-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute left-2 top-2 text-gray-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                        </div>
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- دکمه‌های اقدام -->
            <div class="flex justify-center gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" wire:click="resetForm" 
                        class="flex items-center gap-2 px-6 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 transition">
                    <i class="fa-solid fa-times text-xs"></i>
                    لغو
                </button>

                <button type="submit" 
                        class="flex items-center gap-2 px-6 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    <i class="fa-solid fa-save text-xs"></i>
                    {{ $customerId ? 'بروزرسانی' : 'ذخیره' }}
                </button>
            </div>
        </form>
    </div>

    <!-- مودال موفقیت -->
    @if($showSuccessModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50" wire:click.self="$set('showSuccessModal', false)">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 w-80">
            <div class="flex justify-center mb-3">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-check text-xl text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <h2 class="text-lg font-bold text-green-600 text-center">موفقیت</h2>
            <p class="text-gray-700 dark:text-gray-200 text-center text-sm mt-2 mb-4">
                {{ $successMessage }}
            </p>
            <div class="flex justify-center">
                <button wire:click="closeSuccessModal" 
                        class="px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition">
                    بستن
                </button>
            </div>
        </div>
    </div>
    @endif
</div>