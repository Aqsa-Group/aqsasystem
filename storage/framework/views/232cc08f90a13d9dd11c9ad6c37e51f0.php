<div>

    <div>
        
        <!--[if BLOCK]><![endif]--><?php if($alert): ?>
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
            class="fixed top-0 left-0 right-0 w-full z-[9999] <?php echo e($alert['title'] === 'Error' ? 'bg-red-500' : 'bg-gradient-to-br from-indigo-400 to-indigo-500'); ?> vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e($alert['message']); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-gradient-to-br from-indigo-50 to-indigo-10 vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('message')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>



    <!--[if BLOCK]><![endif]--><?php if($currentUser && $currentUser->role === 'admin' || $currentUser->role === 'superadmin' ): ?>
    <!-- فرم ثبت کاربر -->
    <div class="w-[1360px] p-4 bg-[#F5F5F5] dark:bg-gray-800 rounded-2xl mx-auto space-y-2"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

        <!-- عنوان و آیکون -->
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-bold text-gray-900 vazir dark:text-white tracking-widest">
                <?php echo e(__('messages.title_add_user')); ?>

            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 vazir">
                <?php echo e(__('messages.subtitle_user')); ?>

            </p>
            <div class="bg-gradient-to-br from-indigo-400 to-indigo-500 rounded-full h-20 w-20 mx-auto flex items-center justify-center">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/light.user.svg')); ?>" alt="" class="mt-2">
            </div>
        </div>

        <?php
        $currentUser = Auth::guard('tools')->user();
        ?>

        <!-- فرم اطلاعات -->
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-2 gap-3">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-black vazir dark:text-gray-300 mb-1">
                        <?php echo e(__('messages.name')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="name" placeholder="<?php echo e(__('messages.placeholder_name')); ?>" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
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
                        <?php echo e(__('messages.lastname')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="lastname" placeholder="<?php echo e(__('messages.placeholder_lastname')); ?>"
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

                <!-- Sarafi Name -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        <?php echo e(__('messages.company_name')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="company_name"
                            placeholder="نام شرکت" <?php echo e(Auth::guard('tools')->user()->role === 'admin' ? : ''); ?>

                        class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C]
                        focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                        <?php echo e(Auth::guard('tools')->user()->role === 'admin' ? 'bg-gray-100' : ''); ?>">
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/buildings-2.svg')); ?>" alt="">
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['sarafi_name'];
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
                        <?php echo e(__('messages.address')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="address" placeholder="<?php echo e(__('messages.placeholder_address')); ?>"
                            <?php echo e(Auth::guard('tools')->user()->role === 'admin' ? : ''); ?>

                        class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C]
                        focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                        <?php echo e(Auth::guard('tools')->user()->role === 'admin' ? 'bg-gray-100' : ''); ?>">
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
                        <?php echo e(__('messages.phone_user')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model.lazy="phone"
                            placeholder="<?php echo e(__('messages.placeholder_phone_user')); ?>" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
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

                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1 vazir">
                        <?php echo e(__('messages.username')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="username" placeholder="<?php echo e(__('messages.placeholder_username')); ?>"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/profile.svg')); ?>" alt="">
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['username'];
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

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        <?php echo e(__('messages.password')); ?>

                    </label>
                    <div class="relative">
                        <input type="password" wire:model.lazy="password"
                            placeholder="<?php echo e(__('messages.placeholder_userpassword')); ?>" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/lock.svg')); ?>" alt="">
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['password'];
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

                <!-- Role -->
                <!--[if BLOCK]><![endif]--><?php if($currentUser && $currentUser->role === 'superadmin'): ?>
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1 vazir">
                        <?php echo e(__('messages.category_user')); ?>

                    </label>
                    <div class="relative">
                        <select wire:model="role"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                           focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                            <option value=""><?php echo e(__('messages.select_role')); ?></option>
                            <option value="admin"><?php echo e(__('messages.admin')); ?></option>

                        </select>
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/clipboard.svg')); ?>" alt="">
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['role'];
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
                <?php elseif($currentUser && $currentUser->role === 'admin'): ?>
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1 vazir">
                        <?php echo e(__('messages.category_user')); ?>

                    </label>
                    <div class="relative">
                        <select wire:model="role"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                           focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                            <option value=""><?php echo e(__('messages.select_role')); ?></option>
                            <option value="accounting_manager"><?php echo e(__('messages.accounting_manager')); ?></option>
                            <option value="financial_manager"><?php echo e(__('messages.financial_manager')); ?></option>
                        </select>
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/clipboard.svg')); ?>" alt="">
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['role'];
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
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!-- User Limition -->
                <!--[if BLOCK]><![endif]--><?php if($currentUser && $currentUser->role === 'superadmin'): ?>
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1 vazir">
                        <?php echo e(__('messages.user_limit')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="user_limition"
                            placeholder="<?php echo e(__('messages.placeholder_user_limit')); ?>" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/customers.svg')); ?>" alt="" class="h-8 w-8">
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['user_limition'];
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
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->



            


            </div>

            <!-- دکمه‌ها -->
            <div class="flex justify-center gap-4 mt-3 pt-2">
                <button type="button" wire:click="resetForm"
                    class="flex-1 py-4 bg-[#B10909] text-white rounded-xl hover:bg-gray-700 transition">
                    <?php echo e(__('messages.cancel')); ?>

                </button>
                <button type="submit"
                    class="flex-1 py-4 bg-gradient-to-br from-indigo-400 to-indigo-500 text-white rounded-xl hover:bg-blue-700 transition">
                    <?php echo e(__('messages.save')); ?>

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
                <span class="text-white"><?php echo e(__('messages.filter')); ?></span>
            </button>

            <!--[if BLOCK]><![endif]--><?php if($filterOpen): ?>
            <div class="absolute top-full mt-2 bg-white border rounded-xl shadow-lg p-4 w-72 z-50 flex flex-col gap-3">
                <select wire:model="filterRole" class="border rounded px-3 py-2 w-full">
                    <option value=""><?php echo e(__('messages.all_roles')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>

                <select wire:model="filterTools" class="border rounded px-3 py-2 w-full">
                    <option value=""><?php echo e(__('messages.all_company')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->tools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tools): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($tools); ?>"><?php echo e($tools); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>

                <button wire:click="applyFilter"
                    class="px-3 py-2 bg-gradient-to-br from-indigo-400 to-indigo-500 text-white rounded-lg hover:bg-blue-700 w-full">
                    <?php echo e(__('messages.apply_filter')); ?>

                </button>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- جستجو -->
        <div class="relative w-96">
            <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5">
            <input type="text" wire:model.debounce.500ms="search" wire:keydown.enter="searchUser"
                placeholder="<?php echo e(__('messages.search_placeholder')); ?>"
                class="w-full border border-gray-300 rounded-2xl pl-10 pr-3 py-3 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm">
        </div>
    </div>

    <!-- جدول کاربران -->
    <div class="w-[1360px] mt-4 mx-auto relative overflow-x-auto shadow-md sm:rounded-lg bg-[#F5F5F5] dark:bg-gray-900"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <table class="min-w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-5">

            <!-- هدر جدول -->
            <thead class="bg-gradient-to-br from-indigo-400 to-indigo-500 dark:bg-gray-700 text-white text-[18px] vazir h-20">
                <tr>
                    <th class="px-6 py-6 font-bold">
                        <span class="border border-white h-2 w-5 px-3 rounded-lg">#</span>
                    </th>
                    <th class="px-6 py-6 font-bold"><?php echo e(__('messages.fullname')); ?></th>
                    <th class="px-6 py-6 font-bold"><?php echo e(__('messages.company_name')); ?></th>
                    <th class="px-6 py-6 font-bold"><?php echo e(__('messages.username')); ?></th>
                    <th class="px-6 py-6 font-bold"><?php echo e(__('messages.category_user')); ?></th>
                    <th class="px-6 py-6 font-bold"><?php echo e(__('messages.status')); ?></th>
                    <th class="px-6 py-6 font-bold text-center"><?php echo e(__('messages.actions')); ?></th>
                </tr>
            </thead>


            <!-- بدنه جدول -->
            <tbody>
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-3 py-2 vazir text-[16px] font-medium "><?php echo e($users->firstItem() + $index); ?></td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir"><?php echo e($user->name); ?></td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir"><?php echo e($user->company_name); ?></td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir"><?php echo e($user->username); ?></td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir"><?php echo e($roles[$user->role] ??
                        $user->role); ?></td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir">
                        <!--[if BLOCK]><![endif]--><?php if($user->status): ?>
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">
                            <?php echo e(__('messages.active')); ?>

                        </span>
                        <?php else: ?>
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">
                            <?php echo e(__('messages.inactive')); ?>

                        </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </td>
                    <td class="px-6 py-4 flex justify-center gap-2">
                        <button wire:click="edit(<?php echo e($user->id); ?>)" class="px-2 py-1">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>" class="w-6 h-6" alt="Edit">
                        </button>
                        <button wire:click="confirmDelete(<?php echo e($user->id); ?>)" class="px-2 py-1">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>" class="w-6 h-6"
                                alt="Delete">
                        </button>
                        <button class="px-2 py-1" wire:click="print(<?php echo e($user->id); ?>)">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>" class="w-8 h-8"
                                alt="Print">
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        هیچ مشتری یافت نشد.
                    </td>
                </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>
    </div>



    <!--[if BLOCK]><![endif]--><?php if($currentUser && $currentUser->role === 'admin' || $currentUser->role==='superadmin' ): ?>

    
    <!--[if BLOCK]><![endif]--><?php if($confirmDeleteId): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div
            class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] rounded-[12px] shadow-xl w-[653px] h-[219.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">

            <!-- دکمه بستن -->
            <button wire:click="$set('confirmDeleteId', null)"
                class="absolute left-0 right-4 top-4 h-6 w-6 flex items-center justify-center">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>" alt="بستن" class="w-4 h-4">
            </button>

            <!-- عنوان -->
            <h1 class="text-2xl text-black shabnam font-medium leading-[100%] mt-2">
                <?php echo e(__('messages.confirm_delete_title')); ?>

            </h1>

            <!-- خط جداکننده -->
            <hr class="bg-[#E1DED3] mt-4 mx-4">

            <!-- پیام تأیید -->
            <p class="mb-6 text-xl shabnam mt-5">
                <?php echo e(__('messages.confirm_delete_message')); ?>

            </p>

            <!-- دکمه‌ها -->
            <div class="flex justify-center gap-4">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-12 text-white text-lg shabnam-fd py-3 bg-[#DD2424] rounded-xl transition hover:bg-red-700">
                    <?php echo e(__('messages.no')); ?>

                </button>
                <button wire:click="delete"
                    class="px-12 py-3 bg-gradient-to-br from-indigo-400 to-indigo-500 text-lg shabnam-fd text-white rounded-xl transition hover:bg-blue-700 flex items-center gap-2">
                    <?php echo e(__('messages.yes')); ?>

                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->


</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<?php $__env->stopPush(); ?>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/users.blade.php ENDPATH**/ ?>