<div>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($alert): ?>
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
        <div class="h-16 md:h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-sm md:text-[18px]">
                <?php echo e($alert['message']); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-gradient-to-br from-indigo-50 to-indigo-10 vazir">
        <div class="h-16 md:h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-sm md:text-[18px]">
                <?php echo e(session('message')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentUser && $currentUser->role === 'admin' || $currentUser->role === 'superadmin' ): ?>
    <!-- فرم ثبت کاربر -->
    <div class="w-full max-w-7xl p-3 md:p-4 bg-[#F5F5F5] dark:bg-gray-800 rounded-xl md:rounded-2xl mx-auto space-y-2 md:space-y-4"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

        <!-- عنوان و آیکون -->
        <div class="text-center space-y-2">
            <h2 class="text-lg md:text-2xl font-bold text-gray-900 vazir dark:text-white tracking-wider md:tracking-widest">
                <?php echo e(__('messages.title_add_user')); ?>

            </h2>
            <p class="text-sm md:text-lg text-gray-600 dark:text-gray-400 vazir">
                <?php echo e(__('messages.subtitle_user')); ?>

            </p>
            <div class="bg-gradient-to-br from-indigo-400 to-indigo-500 rounded-full h-16 w-16 md:h-20 md:w-20 mx-auto flex items-center justify-center">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/light.user.svg')); ?>" alt="" class="w-6 h-6 md:w-8 md:h-8 mt-1 md:mt-2">
            </div>
        </div>

        <?php
        $currentUser = Auth::guard('tools')->user();
        ?>

        <!-- فرم اطلاعات -->
        <form wire:submit.prevent="save" class="space-y-3 md:space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">

                <!-- Name -->
                <div>
                    <label class="block text-xs md:text-sm font-medium text-black vazir dark:text-gray-300 mb-1">
                        <?php echo e(__('messages.name')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="name" placeholder="<?php echo e(__('messages.placeholder_name')); ?>" 
                            class="w-full p-2 py-2 md:py-3 rounded-lg md:rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                            focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base">
                        <div class="absolute left-2 top-1/2 transform -translate-y-1/2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/profile.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Lastname -->
                <div>
                    <label class="block text-xs md:text-sm font-medium text-black dark:text-gray-300 mb-1">
                        <?php echo e(__('messages.lastname')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="lastname" placeholder="<?php echo e(__('messages.placeholder_lastname')); ?>"
                            class="w-full p-2 py-2 md:py-3 rounded-lg md:rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                            focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base">
                        <div class="absolute left-2 top-1/2 transform -translate-y-1/2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/profile.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Company Name -->
                <div>
                    <label class="block text-xs md:text-sm font-medium text-black dark:text-gray-300 mb-1">
                        <?php echo e(__('messages.company_name')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="company_name"
                            placeholder="نام شرکت"
                            class="w-full p-2 py-2 md:py-3 rounded-lg md:rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C]
                            focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base
                            <?php echo e(Auth::guard('tools')->user()->role === 'admin' ? 'bg-gray-100' : ''); ?>">
                        <div class="absolute left-2 top-1/2 transform -translate-y-1/2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/buildings-2.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-xs md:text-sm font-medium text-black dark:text-gray-300 mb-1">
                        <?php echo e(__('messages.address')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="address" placeholder="<?php echo e(__('messages.placeholder_address')); ?>"
                            class="w-full p-2 py-2 md:py-3 rounded-lg md:rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C]
                            focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base
                            <?php echo e(Auth::guard('tools')->user()->role === 'admin' ? 'bg-gray-100' : ''); ?>">
                        <div class="absolute left-2 top-1/2 transform -translate-y-1/2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/location.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-xs md:text-sm font-medium text-black dark:text-gray-300 mb-1">
                        <?php echo e(__('messages.phone_user')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model.lazy="phone"
                            placeholder="<?php echo e(__('messages.placeholder_phone_user')); ?>" 
                            class="w-full p-2 py-2 md:py-3 rounded-lg md:rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                            focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base">
                        <div class="absolute left-2 top-1/2 transform -translate-y-1/2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/call.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-xs md:text-sm font-medium text-black dark:text-gray-300 mb-1 vazir">
                        <?php echo e(__('messages.username')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="username" placeholder="<?php echo e(__('messages.placeholder_username')); ?>"
                            class="w-full p-2 py-2 md:py-3 rounded-lg md:rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                            focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base">
                        <div class="absolute left-2 top-1/2 transform -translate-y-1/2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/profile.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs md:text-sm font-medium text-black dark:text-gray-300 mb-1">
                        <?php echo e(__('messages.password')); ?>

                    </label>
                    <div class="relative">
                        <input type="password" wire:model.lazy="password"
                            placeholder="<?php echo e(__('messages.placeholder_userpassword')); ?>" 
                            class="w-full p-2 py-2 md:py-3 rounded-lg md:rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                            focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base">
                        <div class="absolute left-2 top-1/2 transform -translate-y-1/2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/lock.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Role -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentUser && $currentUser->role === 'superadmin'): ?>
                <div>
                    <label class="block text-xs md:text-sm font-medium text-black dark:text-gray-300 mb-1 vazir">
                        <?php echo e(__('messages.category_user')); ?>

                    </label>
                    <div class="relative">
                        <select wire:model="role"
                            class="w-full p-2 py-2 md:py-3 rounded-lg md:rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                            focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base appearance-none">
                            <option value=""><?php echo e(__('messages.select_role')); ?></option>
                            <option value="admin"><?php echo e(__('messages.admin')); ?></option>
                        </select>
                        <div class="absolute left-2 top-1/2 transform -translate-y-1/2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/clipboard.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php elseif($currentUser && $currentUser->role === 'admin'): ?>
                <div>
                    <label class="block text-xs md:text-sm font-medium text-black dark:text-gray-300 mb-1 vazir">
                        <?php echo e(__('messages.category_user')); ?>

                    </label>
                    <div class="relative">
                        <select wire:model="role"
                            class="w-full p-2 py-2 md:py-3 rounded-lg md:rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                            focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base appearance-none">
                            <option value=""><?php echo e(__('messages.select_role')); ?></option>
                            <option value="accounting_manager"><?php echo e(__('messages.accounting_manager')); ?></option>
                            <option value="financial_manager"><?php echo e(__('messages.financial_manager')); ?></option>
                        </select>
                        <div class="absolute left-2 top-1/2 transform -translate-y-1/2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/clipboard.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- User Limition -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentUser && $currentUser->role === 'superadmin'): ?>
                <div>
                    <label class="block text-xs md:text-sm font-medium text-black dark:text-gray-300 mb-1 vazir">
                        <?php echo e(__('messages.user_limit')); ?>

                    </label>
                    <div class="relative">
                        <input type="text" wire:model="user_limition"
                            placeholder="<?php echo e(__('messages.placeholder_user_limit')); ?>" 
                            class="w-full p-2 py-2 md:py-3 rounded-lg md:rounded-xl border focus:ring-2 bg-transparent border-[#8C8C8C] 
                            focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base">
                        <div class="absolute left-2 top-1/2 transform -translate-y-1/2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/customers.svg')); ?>" alt="" class="h-6 w-6 md:h-8 md:w-8">
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['user_limition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- دکمه‌ها -->
            <div class="flex flex-col sm:flex-row justify-center gap-3 md:gap-4 mt-3 md:mt-4 pt-2">
                <button type="button" wire:click="resetForm"
                    class="flex-1 py-3 md:py-4 bg-[#B10909] text-white rounded-lg md:rounded-xl hover:bg-gray-700 transition text-sm md:text-base">
                    <?php echo e(__('messages.cancel')); ?>

                </button>
                <button type="submit"
                    class="flex-1 py-3 md:py-4 bg-gradient-to-br from-indigo-400 to-indigo-500 text-white rounded-lg md:rounded-xl hover:bg-blue-700 transition text-sm md:text-base">
                    <?php echo e(__('messages.save')); ?>

                </button>
            </div>
        </form>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- فیلتر و سرچ -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center mt-4 md:mt-5 gap-3 w-full max-w-7xl mx-auto px-2 sm:px-0">

        <!-- دکمه فیلتر -->
        <div class="relative">
            <button wire:click="$toggle('filterOpen')"
                class="w-full sm:w-auto px-3 py-2 border rounded-lg bg-gradient-to-br from-indigo-400 to-indigo-500 transition flex items-center justify-center gap-2 text-white text-sm md:text-base">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/filter.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                <span class="text-white"><?php echo e(__('messages.filter')); ?></span>
            </button>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filterOpen): ?>
            <div class="absolute top-full mt-2 bg-white border rounded-xl shadow-lg p-3 md:p-4 w-64 md:w-72 z-50 flex flex-col gap-2 md:gap-3">
                <select wire:model="filterRole" class="border rounded px-2 md:px-3 py-1 md:py-2 w-full text-sm md:text-base">
                    <option value=""><?php echo e(__('messages.all_roles')); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>

                <select wire:model="filterTools" class="border rounded px-2 md:px-3 py-1 md:py-2 w-full text-sm md:text-base">
                    <option value=""><?php echo e(__('messages.all_company')); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->tools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tools): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($tools); ?>"><?php echo e($tools); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>

                <button wire:click="applyFilter"
                    class="px-2 md:px-3 py-1 md:py-2 bg-gradient-to-br from-indigo-400 to-indigo-500 text-white rounded-lg hover:bg-blue-700 w-full text-sm md:text-base">
                    <?php echo e(__('messages.apply_filter')); ?>

                </button>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- جستجو -->
        <div class="relative flex-1">
            <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 md:w-5 md:h-5">
            <input type="text" wire:model.debounce.500ms="search" wire:keydown.enter="searchUser"
                placeholder="<?php echo e(__('messages.search_placeholder')); ?>"
                class="w-full border border-gray-300 rounded-lg md:rounded-2xl pl-10 pr-3 py-2 md:py-3 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm md:text-base">
        </div>
    </div>

    <!-- جدول کاربران -->
    <div class="w-full max-w-7xl mt-3 md:mt-4 mx-auto relative overflow-x-auto shadow-md sm:rounded-lg bg-[#F5F5F5] dark:bg-gray-900 px-2 sm:px-0"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <table class="min-w-full text-xs md:text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-4 md:mb-5">

            <!-- هدر جدول -->
            <thead class="bg-gradient-to-br from-indigo-400 to-indigo-500 dark:bg-gray-700 text-white text-sm md:text-[18px] vazir h-12 md:h-20">
                <tr>
                    <th class="px-3 md:px-6 py-3 md:py-6 font-bold">
                        <span class="border border-white h-2 w-5 px-2 md:px-3 rounded-lg text-xs md:text-sm">#</span>
                    </th>
                    <th class="px-3 md:px-6 py-3 md:py-6 font-bold"><?php echo e(__('messages.fullname')); ?></th>
                    <th class="px-3 md:px-6 py-3 md:py-6 font-bold hidden sm:table-cell"><?php echo e(__('messages.company_name')); ?></th>
                    <th class="px-3 md:px-6 py-3 md:py-6 font-bold"><?php echo e(__('messages.username')); ?></th>
                    <th class="px-3 md:px-6 py-3 md:py-6 font-bold hidden md:table-cell"><?php echo e(__('messages.category_user')); ?></th>
                    <th class="px-3 md:px-6 py-3 md:py-6 font-bold"><?php echo e(__('messages.status')); ?></th>
                    <th class="px-3 md:px-6 py-3 md:py-6 font-bold text-center"><?php echo e(__('messages.actions')); ?></th>
                </tr>
            </thead>

            <!-- بدنه جدول -->
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-2 md:px-3 py-2 vazir text-xs md:text-[16px] font-medium"><?php echo e($users->firstItem() + $index); ?></td>
                    <td class="px-3 md:px-6 py-2 md:py-4 vazir text-xs md:text-[16px] font-medium text-black"><?php echo e($user->name); ?></td>
                    <td class="px-3 md:px-6 py-2 md:py-4 vazir text-xs md:text-[16px] font-medium text-black hidden sm:table-cell"><?php echo e($user->company_name); ?></td>
                    <td class="px-3 md:px-6 py-2 md:py-4 vazir text-xs md:text-[16px] font-medium text-black"><?php echo e($user->username); ?></td>
                    <td class="px-3 md:px-6 py-2 md:py-4 vazir text-xs md:text-[16px] font-medium text-black hidden md:table-cell">
                        <?php echo e($roles[$user->role] ?? $user->role); ?>

                    </td>
                    <td class="px-3 md:px-6 py-2 md:py-4 vazir text-xs md:text-[16px] font-medium text-black">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->status): ?>
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">
                            <?php echo e(__('messages.active')); ?>

                        </span>
                        <?php else: ?>
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">
                            <?php echo e(__('messages.inactive')); ?>

                        </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td class="px-3 md:px-6 py-2 md:py-4 flex justify-center gap-1 md:gap-2">
                        <button wire:click="edit(<?php echo e($user->id); ?>)" class="p-1 md:px-2 md:py-1">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>" class="w-4 h-4 md:w-6 md:h-6" alt="Edit">
                        </button>
                        <button wire:click="confirmDelete(<?php echo e($user->id); ?>)" class="p-1 md:px-2 md:py-1">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>" class="w-4 h-4 md:w-6 md:h-6"
                                alt="Delete">
                        </button>
                        <button class="p-1 md:px-2 md:py-1" wire:click="print(<?php echo e($user->id); ?>)">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>" class="w-5 h-5 md:w-8 md:h-8"
                                alt="Print">
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-3 md:px-6 py-3 md:py-4 text-center text-gray-500 dark:text-gray-400 text-sm md:text-base">
                        هیچ مشتری یافت نشد.
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentUser && $currentUser->role === 'admin' || $currentUser->role === 'superadmin' ): ?>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($confirmDeleteId): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-2 md:p-4">
        <div
            class="bg-[#FFFFFF] pt-4 md:pt-[21px] pr-3 md:pr-[15px] pl-3 md:pl-[15px] rounded-lg md:rounded-[12px] shadow-xl w-full max-w-md md:w-[653px] h-auto md:h-[219.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">

            <!-- دکمه بستن -->
            <button wire:click="$set('confirmDeleteId', null)"
                class="absolute left-3 md:left-4 top-3 md:top-4 h-5 w-5 md:h-6 md:w-6 flex items-center justify-center">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>" alt="بستن" class="w-3 h-3 md:w-4 md:h-4">
            </button>

            <!-- عنوان -->
            <h1 class="text-lg md:text-2xl text-black shabnam font-medium leading-[100%] mt-1 md:mt-2">
                <?php echo e(__('messages.confirm_delete_title')); ?>

            </h1>

            <!-- خط جداکننده -->
            <hr class="bg-[#E1DED3] mt-3 md:mt-4 mx-2 md:mx-4">

            <!-- پیام تأیید -->
            <p class="mb-4 md:mb-6 text-sm md:text-xl shabnam mt-3 md:mt-5">
                <?php echo e(__('messages.confirm_delete_message')); ?>

            </p>

            <!-- دکمه‌ها -->
            <div class="flex flex-col sm:flex-row justify-center gap-3 md:gap-4 mb-3 md:mb-0">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-6 md:px-12 text-white text-sm md:text-lg shabnam-fd py-2 md:py-3 bg-[#DD2424] rounded-lg md:rounded-xl transition hover:bg-red-700">
                    <?php echo e(__('messages.no')); ?>

                </button>
                <button wire:click="delete"
                    class="px-6 md:px-12 py-2 md:py-3 bg-gradient-to-br from-indigo-400 to-indigo-500 text-sm md:text-lg shabnam-fd text-white rounded-lg md:rounded-xl transition hover:bg-blue-700 flex items-center justify-center gap-2">
                    <?php echo e(__('messages.yes')); ?>

                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<?php $__env->stopPush(); ?><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/users.blade.php ENDPATH**/ ?>