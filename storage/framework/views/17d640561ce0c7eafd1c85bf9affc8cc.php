<div>
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
            <div class="bg-[#2563EB] rounded-full h-20 w-20 mx-auto flex items-center justify-center">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/light.user.svg')); ?>" alt="" class="mt-2">
            </div>
        </div>

        <?php
        $currentUser = Auth::guard('sarafi')->user();
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
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                    <?php $__errorArgs = ['lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Sarafi Name -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        <?php echo e(__('messages.sarafi_name')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="sarafi_name"
                            placeholder="<?php echo e(__('messages.placeholder_sarafi_name')); ?>" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/buildings-2.svg')); ?>" alt="">
                        </div>
                    </div>
                    <?php $__errorArgs = ['sarafi_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        <?php echo e(__('messages.address')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="address" placeholder="<?php echo e(__('messages.placeholder_address')); ?>"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/location.svg')); ?>" alt="">
                        </div>
                    </div>
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        <?php echo e(__('messages.phone_user')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="phone" placeholder="<?php echo e(__('messages.placeholder_phone_user')); ?>"
                            class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/call.svg')); ?>" alt="">
                        </div>
                    </div>
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                    <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-gray-300 mb-1">
                        <?php echo e(__('messages.password')); ?>

                    </label>
                    <div class="relative">
                        <input type="password" wire:model="password"
                            placeholder="<?php echo e(__('messages.placeholder_userpassword')); ?>" class="w-full p-2 py-3 rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                                      focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/lock.svg')); ?>" alt="">
                        </div>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Role -->
                <?php if($currentUser && $currentUser->role === 'superadmin'): ?>
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
                    <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                            <option value="warehouse_manager"><?php echo e(__('messages.warehouse_manager')); ?></option>
                            <option value="internal_officer"><?php echo e(__('messages.internal_officer')); ?></option>
                            <option value="external_officer"><?php echo e(__('messages.external_officer')); ?></option>
                        </select>
                        <div class="absolute left-2 top-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/clipboard.svg')); ?>" alt="">
                        </div>
                    </div>
                    <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <?php endif; ?>

                <!-- User Limition -->
                <?php if($currentUser && $currentUser->role === 'superadmin'): ?>
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
                    <?php $__errorArgs = ['user_limition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <?php endif; ?>

            </div>

            <!-- دکمه‌ها -->
            <div class="flex justify-center gap-4 mt-3 pt-2">
                <button type="button" wire:click="resetForm"
                    class="flex-1 py-4 bg-[#B10909] text-white rounded-xl hover:bg-gray-700 transition">
                    <?php echo e(__('messages.cancel')); ?>

                </button>
                <button type="submit"
                    class="flex-1 py-4 bg-[#2563EB] text-white rounded-xl hover:bg-blue-700 transition">
                    <?php echo e(__('messages.save')); ?>

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
                <img src="<?php echo e(asset('assets/sarafi/all_icon/filter.svg')); ?>" alt="">
                <span class="text-white"><?php echo e(__('messages.filter')); ?></span>
            </button>

            <?php if($filterOpen): ?>
            <div class="absolute top-full mt-2 bg-white border rounded-xl shadow-lg p-4 w-72 z-50 flex flex-col gap-3">
                <select wire:model="filterRole" class="border rounded px-3 py-2 w-full">
                    <option value=""><?php echo e(__('messages.all_roles')); ?></option>
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <select wire:model="filterSarafi" class="border rounded px-3 py-2 w-full">
                    <option value=""><?php echo e(__('messages.all_sarafis')); ?></option>
                    <?php $__currentLoopData = $this->sarafis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sarafi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($sarafi); ?>"><?php echo e($sarafi); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <button wire:click="applyFilter"
                    class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 w-full">
                    <?php echo e(__('messages.apply_filter')); ?>

                </button>
            </div>
            <?php endif; ?>
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
            <thead class="bg-[#2563EB] dark:bg-gray-700 text-white text-[18px] vazir h-20">
                <tr>
                    <th class="px-6 py-6 font-bold">
                        <span class="border border-white h-2 w-5 px-3 rounded-lg">#</span>
                    </th>
                    <th class="px-6 py-6 font-bold"><?php echo e(__('messages.fullname')); ?></th>
                    <th class="px-6 py-6 font-bold"><?php echo e(__('messages.sarafi_name')); ?></th>
                    <th class="px-6 py-6 font-bold"><?php echo e(__('messages.username')); ?></th>
                    <th class="px-6 py-6 font-bold"><?php echo e(__('messages.category_user')); ?></th>
                    <th class="px-6 py-6 font-bold"><?php echo e(__('messages.status')); ?></th>
                    <th class="px-6 py-6 font-bold text-center"><?php echo e(__('messages.actions')); ?></th>
                </tr>
            </thead>


            <!-- بدنه جدول -->
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-3 py-2 vazir text-[16px] font-medium "><?php echo e($users->firstItem() + $index); ?></td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir"><?php echo e($user->name); ?></td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir"><?php echo e($user->sarafi_name); ?></td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir"><?php echo e($user->username); ?></td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir"><?php echo e($roles[$user->role] ?? $user->role); ?></td>
                    <td class="px-6 py-4 vazir text-[16px] font-medium  text-black vazir">
                        <?php if($user->status): ?>
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">
                            <?php echo e(__('messages.active')); ?>

                        </span>
                        <?php else: ?>
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">
                            <?php echo e(__('messages.inactive')); ?>

                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 flex justify-center gap-2">
                        <button wire:click="edit(<?php echo e($user->id); ?>)" class="px-2 py-1">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>" class="w-6 h-6" alt="Edit">
                        </button>
                        <button wire:click="confirmDelete(<?php echo e($user->id); ?>)" class="px-2 py-1">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>" class="w-6 h-6"
                                alt="Delete">
                        </button>
                        <button class="px-2 py-1">
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
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if($alert): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-3xl shadow-xl w-[600px] text-center animate-fadeIn z-50">
            <h3 class="text-2xl font-bold mb-3 text-green-800"><?php echo e($alert['title']); ?></h3>
            <p class="text-gray-600 mb-4 text-2xl"><?php echo e($alert['message']); ?></p>
            <button wire:click="$set('alert', null)"
                class="px-56 py-4 bg-[#2563EB] text-white rounded-xl hover:bg-blue-700 transition">
                <?php echo e(__('messages.ok')); ?>

            </button>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($confirmDeleteId): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-3xl shadow-xl w-[600px] text-center animate-fadeIn z-50">
            <h3 class="text-xl font-bold mb-4 text-red-600 vazir"><?php echo e(__('messages.confirm_delete_title')); ?></h3>
            <p class="text-gray-600 mb-6 text-2xl vazir"><?php echo e(__('messages.confirm_delete_message')); ?></p>
            <div class="flex justify-center gap-4">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-28 py-4 bg-gray-300 rounded-xl hover:bg-gray-400 transition"><?php echo e(__('messages.no')); ?></button>
                <button wire:click="delete"
                    class="px-28 py-4 bg-red-600 text-white rounded-xl hover:bg-red-700 transition flex items-center gap-2">
                    <i class="fas fa-trash-alt"></i> <?php echo e(__('messages.yes')); ?>

                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<?php $__env->stopPush(); ?>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/users.blade.php ENDPATH**/ ?>