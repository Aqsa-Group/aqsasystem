<div>
    <!-- Alerts -->
    <!--[if BLOCK]><![endif]--><?php if($alert): ?>
    <div x-data="{
        show: true,
        init() {
            $wire.watch('alert', (value) => {
                if (value) {
                    this.show = true;
                    setTimeout(() => {
                        this.show = false;
                        setTimeout(() => $wire.clearAlert(), 300);
                    }, 4000);
                }
            });
            
            setTimeout(() => {
                this.show = false;
                setTimeout(() => $wire.clearAlert(), 300);
            }, 4000);
        }
    }" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] <?php echo e($alert['title'] === 'Error' ? 'bg-red-500' : 'bg-gradient-to-br from-indigo-400 to-indigo-500'); ?> vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e($alert['message']); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if($currentUser && ($currentUser->role === 'admin' || $currentUser->role === 'superadmin')): ?>
    <!-- فرم ثبت کارمند -->
    <div class="w-[1360px] p-4 bg-[#F5F5F5] dark:bg-gray-800 rounded-2xl mx-auto space-y-2"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

        <!-- عنوان و آیکون -->
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-bold text-gray-900 vazir dark:text-white tracking-widest">
                ثبت کارمند
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 vazir">
                لطفا اطلاعات کارمند را با دقت وارد نمائید
            </p>
        </div>

        <!-- فرم اطلاعات -->
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="flex justify-center gap-72 mb-6">
                <!-- عکس پروفایل -->
                <div class="flex flex-col items-center">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        عکس کارمند
                    </label>
                    <div class="relative w-20 h-20">
                        <!--[if BLOCK]><![endif]--><?php if($newProfile): ?>
                        <img src="<?php echo e($newProfile->temporaryUrl()); ?>"
                            class="w-20 h-20 rounded-full object-cover border-2 border-blue-400">
                        <?php elseif($profile && $editId): ?>
                        <img src="<?php echo e(asset('storage/' . $profile)); ?>"
                            class="w-20 h-20 rounded-full object-cover border-2 border-gray-300">
                        <?php else: ?>
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-500 flex items-center justify-center">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/profile-circle.svg')); ?>" alt="">
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <input type="file" wire:model="newProfile" accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['newProfile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- عکس شناسنامه -->
                <div class="flex flex-col items-center">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        عکس تذکره کارمند
                    </label>
                    <div class="relative w-20 h-20">
                        <!--[if BLOCK]><![endif]--><?php if($newIdCardImage): ?>
                        <img src="<?php echo e($newIdCardImage->temporaryUrl()); ?>"
                            class="w-20 h-20 rounded-lg object-cover border-2 border-green-400">
                        <?php elseif($idCardImage && $editId): ?>
                        <img src="<?php echo e(asset('storage/' . $idCardImage)); ?>"
                            class="w-20 h-20 rounded-lg object-cover border-2 border-gray-300">
                        <?php else: ?>
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-500 flex items-center justify-center">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/id.svg')); ?>" alt="">
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <input type="file" wire:model="newIdCardImage" accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['newIdCardImage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-black vazir dark:text-gray-300 mb-1">
                        نام
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="name" placeholder="نام کارمند را وارد کنید" 
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                   focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/profile.svg')); ?>" alt="">
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Lastname -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        تخلص
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="lastname" placeholder="تخلص کارمند را وارد کنید"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/profile.svg')); ?>" alt="">
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        آدرس
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="address" placeholder="آدرس کارمند را وارد کنید"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C]
                        focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/location.svg')); ?>" alt="">
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        شماره تماس
                    </label>
                    <div class="relative">
                        <input type="text" wire:model.lazy="phone"
                            placeholder="شماره تماس کارمند را وارد کنید" 
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/call.svg')); ?>" alt="">
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Job -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1 vazir">
                        وظیفه کارمند
                    </label>
                    <div class="relative">
                        <select wire:model="job"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                           focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                            <option value="">انتخاب کنید</option>
                            <option value="حسابدار">حسابدار</option>
                            <option value="مدیر مالی">مدیر مالی</option>
                            <option value="صفاکار">صفاکار</option>
                            <option value="متفرقه">متفرقه</option>
                        </select>
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/clipboard.svg')); ?>" alt="">
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- salary -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        معاش کارمند
                    </label>
                    <div class="relative">
                        <input type="text" wire:model.lazy="salary"
                            placeholder="مبلغ معاش کارمند" 
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/dollar-circle.svg')); ?>" class="w-8 h-8" alt="">
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            <!-- دکمه‌ها -->
            <div class="flex justify-center gap-4 mt-3 pt-2">
                <button type="button" wire:click="resetInputFields"
                    class="flex-1 py-4 bg-[#B10909] text-white rounded-xl hover:bg-gray-700 transition">
                    لغو
                </button>
                <button type="submit"
                    class="flex-1 py-4 bg-gradient-to-br from-indigo-400 to-indigo-500 text-white rounded-xl hover:bg-blue-700 transition">
                    ذخیره
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- فیلتر و سرچ -->
    <div class="flex items-center mt-5 gap-3 w-[1360px] mx-auto">
        <!-- دکمه فیلتر -->
        <div class="relative">
            <button wire:click="$toggle('filterOpen')"
                class="px-3 py-2 border rounded-lg bg-gradient-to-br from-indigo-400 to-indigo-500 transition flex items-center gap-2 text-white">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/filter.svg')); ?>" alt="">
                <span class="text-white">فیلتر</span>
            </button>

            <!--[if BLOCK]><![endif]--><?php if($filterOpen): ?>
            <div class="absolute top-full mt-2 bg-white border rounded-xl shadow-lg p-4 w-72 z-50 flex flex-col gap-3">
                <select wire:model="filterJob" class="border rounded px-3 py-2 w-full">
                    <option value="">همه وظایف</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($job); ?>"><?php echo e($job); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>

                <button wire:click="applyFilter"
                    class="px-3 py-2 bg-gradient-to-br from-indigo-400 to-indigo-500 text-white rounded-lg hover:bg-blue-700 w-full">
                    اعمال فیلتر
                </button>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- جستجو -->
        <div class="relative w-96">
            <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5">
            <input type="text" wire:model.debounce.500ms="search" wire:keydown.enter="searchUser"
                placeholder="جستجوی کارمند بر اساس نام، تخلص، وظیفه یا شماره تماس"
                class="w-full border border-gray-300 rounded-2xl pl-10 pr-3 py-3 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm">
        </div>
    </div>

    <!-- جدول کارمندان -->
    <div class="w-[1360px] mt-4 mx-auto relative overflow-x-auto shadow-md sm:rounded-lg bg-[#F5F5F5] dark:bg-gray-900"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <table class="min-w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-5">
            <!-- هدر جدول -->
            <thead class="bg-gradient-to-br from-indigo-400 to-indigo-500 dark:bg-gray-700 text-white text-[18px] vazir h-20">
                <tr>
                    <th class="px-6 py-6 font-bold">
                        <span class="border border-white h-2 w-5 px-3 rounded-lg">#</span>
                    </th>
                    <th class="px-6 py-6 font-bold">نام کامل</th>
                    <th class="px-6 py-6 font-bold">وظیفه</th>
                    <th class="px-6 py-6 font-bold">شماره تماس</th>
                    <th class="px-6 py-6 font-bold">معاش</th>
                    <th class="px-6 py-6 font-bold">آدرس</th>
                    <th class="px-6 py-6 font-bold text-center">عملیات</th>
                </tr>
            </thead>

            <!-- بدنه جدول -->
            <tbody>
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-3 py-2 vazir text-[16px] font-medium"><?php echo e($staffs->firstItem() + $index); ?></td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium text-black vazir">
                        <?php echo e($staff->name); ?> <?php echo e($staff->lastname); ?>

                    </td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium text-black vazir"><?php echo e($staff->job); ?></td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium text-black vazir"><?php echo e($staff->phone ?? '-'); ?></td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium text-black vazir"><?php echo e(number_format($staff->salary)); ?> افغانی</td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium text-black vazir"><?php echo e($staff->address ?? '-'); ?></td>
                    <td class="px-6 py-4 flex justify-center gap-2">
                        <!--[if BLOCK]><![endif]--><?php if($currentUser && ($currentUser->role === 'admin' || $currentUser->role === 'superadmin')): ?>
                        <button wire:click="edit(<?php echo e($staff->id); ?>)" class="px-2 py-1">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>" class="w-6 h-6" alt="Edit">
                        </button>
                        <button wire:click="confirmDelete(<?php echo e($staff->id); ?>)" class="px-2 py-1">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>" class="w-6 h-6" alt="Delete">
                        </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <button class="px-2 py-1" wire:click="print(<?php echo e($staff->id); ?>)">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>" class="w-8 h-8" alt="Print">
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        هیچ کارمندی یافت نشد.
                    </td>
                </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4">
            <?php echo e($staffs->links()); ?>

        </div>
    </div>

    <!--[if BLOCK]><![endif]--><?php if($currentUser && ($currentUser->role === 'admin' || $currentUser->role === 'superadmin')): ?>
    <!-- مودال تأیید حذف -->
    <!--[if BLOCK]><![endif]--><?php if($confirmDeleteId): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] rounded-[12px] shadow-xl w-[653px] h-[219px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
            <!-- دکمه بستن -->
            <button wire:click="$set('confirmDeleteId', null)"
                class="absolute left-4 top-4 h-6 w-6 flex items-center justify-center">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>" alt="بستن" class="w-4 h-4">
            </button>

            <!-- عنوان -->
            <h1 class="text-2xl text-black shabnam font-medium leading-[100%] mt-2">
                تأیید حذف
            </h1>

            <!-- خط جداکننده -->
            <hr class="bg-[#E1DED3] mt-4 mx-4">

            <!-- پیام تأیید -->
            <p class="mb-6 text-xl shabnam mt-5">
                آیا از حذف این کارمند اطمینان دارید؟
            </p>

            <!-- دکمه‌ها -->
            <div class="flex justify-center gap-4">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-12 text-white text-lg shabnam-fd py-3 bg-[#DD2424] rounded-xl transition hover:bg-red-700">
                    خیر
                </button>
                <button wire:click="delete"
                    class="px-12 py-3 bg-gradient-to-br from-indigo-400 to-indigo-500 text-lg shabnam-fd text-white rounded-xl transition hover:bg-blue-700 flex items-center gap-2">
                    بله
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/staff.blade.php ENDPATH**/ ?>