<div class="min-h-screen dark:bg-gray-900 py-4 w-full px-3 sm:px-4 md:px-6">
    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-gradient-to-br from-indigo-400 to-indigo-500 vazir">
        <div class="h-16 md:h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-sm md:text-[18px]">
                <?php echo e(session('message')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div class="w-full max-w-6xl mx-auto p-3 md:p-4 bg-[#F5F5F5] dark:bg-gray-800 rounded-xl md:rounded-2xl" 
         style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        
        <!-- هدر -->
        <div class="text-center mb-4 md:mb-6">
            <h2 class="text-lg md:text-2xl font-bold text-gray-900 vazir dark:text-white tracking-wider md:tracking-widest">
                <?php echo e($customerId ? __('messages.title_edit') : __('messages.title_add')); ?>

            </h2>

            <p class="text-sm md:text-lg text-gray-600 dark:text-gray-400 mt-2 md:mt-4 vazir">
                <?php echo e(__('messages.subtitle')); ?>

            </p>
        </div>

        <form wire:submit.prevent="saveCustomer" class="w-full">
            <!-- بخش آپلود تصاویر -->
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 md:gap-8 lg:gap-72 mb-4 md:mb-6">
                <!-- عکس پروفایل -->
                <div class="flex flex-col items-center">
                    <label class="text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <?php echo e(__('messages.profile_image')); ?>

                    </label>
                    <div class="relative w-16 h-16 md:w-20 md:h-20">
                        <!--[if BLOCK]><![endif]--><?php if($newProfile): ?>
                        <img src="<?php echo e($newProfile->temporaryUrl()); ?>"
                            class="w-16 h-16 md:w-20 md:h-20 rounded-full object-cover border-2 border-blue-400">
                        <?php elseif($profile && $customerId): ?>
                        <img src="<?php echo e(asset('storage/' . $profile)); ?>"
                            class="w-16 h-16 md:w-20 md:h-20 rounded-full object-cover border-2 border-gray-300">
                        <?php else: ?>
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-500 flex items-center justify-center">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/profile-circle.svg')); ?>" alt="" class="w-8 h-8 md:w-10 md:h-10">
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <input type="file" wire:model="newProfile" accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                </div>

                <!-- عکس شناسنامه -->
                <div class="flex flex-col items-center">
                    <label class="text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <?php echo e(__('messages.idcard_image')); ?>

                    </label>
                    <div class="relative w-16 h-16 md:w-20 md:h-20">
                        <!--[if BLOCK]><![endif]--><?php if($newIdCardImage): ?>
                        <img src="<?php echo e($newIdCardImage->temporaryUrl()); ?>"
                            class="w-16 h-16 md:w-20 md:h-20 rounded-lg object-cover border-2 border-green-400">
                        <?php elseif($idCardImage && $customerId): ?>
                        <img src="<?php echo e(asset('storage/' . $idCardImage)); ?>"
                            class="w-16 h-16 md:w-20 md:h-20 rounded-lg object-cover border-2 border-gray-300">
                        <?php else: ?>
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-500 flex items-center justify-center">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/id.svg')); ?>" alt="" class="w-8 h-8 md:w-10 md:h-10">
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <input type="file" wire:model="newIdCardImage" accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- فیلدهای اطلاعات -->
            <div class="space-y-3 md:space-y-4 w-full">
                <!-- ردیف 1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 w-full">
                    <div class="w-full">
                        <label class="block text-xs md:text-sm font-medium text-black vazir dark:text-gray-300 mb-1 md:mb-2">
                            <?php echo e(__('messages.fullname')); ?>

                        </label>
                        <div class="relative w-full">
                            <input type="text" wire:model="fullname"
                                placeholder="<?php echo e(__('messages.placeholder_fullname')); ?>"
                                class="w-full p-2 md:p-3 rounded-lg md:rounded-xl border py-3 md:py-4 focus:ring-2 bg-transparent border-[#8C8C8C] focus:border-none focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base">
                            <div class="absolute left-2 md:left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/profile.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                            </div>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['fullname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div class="w-full">
                        <label class="block text-xs md:text-sm font-medium text-black dark:text-gray-300 mb-1 md:mb-2">
                            <?php echo e(__('messages.account_number')); ?>

                        </label>
                        <div class="flex gap-2 w-full">
                            <div class="relative flex-1">
                                <input type="text" wire:model.lazy="account"
                                    placeholder="<?php echo e(__('messages.placeholder_account')); ?>"
                                    class="w-full p-2 md:p-3 rounded-lg md:rounded-xl py-3 md:py-4 focus:ring-2 border bg-transparent border-[#8C8C8C] focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base"
                                    maxlength="16" <?php if(!$customerId): ?> readonly <?php endif; ?>>
                                <div class="absolute left-2 md:left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/card.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                                </div>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php if(!$customerId): ?>
                            <button type="button" wire:click="generateNewAccountNumber"
                                class="px-3 md:px-4 py-2 md:py-3 border bg-transparent border-[#8C8C8C] text-white rounded-lg transition">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/refresh-2.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                            </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['account'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- ردیف 2 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 w-full">
                    <div class="w-full">
                        <label class="block text-xs md:text-sm font-medium text-black dark:text-gray-300 mb-1 md:mb-2 vazir">
                            <?php echo e(__('messages.city')); ?>

                        </label>
                        <div class="relative w-full">
                            <input type="text" wire:model="city" placeholder="<?php echo e(__('messages.placeholder_city')); ?>"
                                class="w-full p-2 md:p-3 rounded-lg md:rounded-xl py-3 md:py-4 focus:ring-2 border bg-transparent border-[#8C8C8C] focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base">
                            <div class="absolute left-2 md:left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/Group.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                            </div>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div class="w-full">
                        <label class="block text-xs md:text-sm font-medium text-black vazir dark:text-gray-300 mb-1 md:mb-2">
                            <?php echo e(__('messages.phone')); ?>

                        </label>
                        <div class="relative w-full">
                            <input type="text" wire:model.lazy="phone"
                                placeholder="<?php echo e(__('messages.placeholder_phone')); ?>"
                                class="w-full p-2 md:p-3 rounded-lg md:rounded-xl py-3 md:py-4 focus:ring-2 border bg-transparent border-[#8C8C8C] focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base">
                            <div class="absolute left-2 md:left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/call.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                            </div>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- ردیف 3 - Tazkira Field -->
                <div class="w-full">
                    <label class="block text-xs md:text-sm font-medium text-black vazir dark:text-gray-300 mb-1 md:mb-2">
                        <?php echo e(__('messages.tazkira')); ?>

                    </label>
                    <div class="relative w-full">
                        <input type="text" wire:model.lazy="tazkira"
                            placeholder="<?php echo e(__('messages.placeholder_tazkira')); ?>"
                            class="w-full p-2 md:p-3 rounded-lg md:rounded-xl py-3 md:py-4 focus:ring-2 border bg-transparent border-[#8C8C8C] focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm md:text-base">
                        <div class="absolute left-2 md:left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/qlementine-icons_id-card-16.svg')); ?>" alt="" class="w-4 h-4 md:w-5 md:h-5">
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['tazkira'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            <!-- دکمه‌های اقدام -->
            <div class="flex flex-col sm:flex-row justify-center gap-3 md:gap-4 mt-6 md:mt-8 pt-4 md:pt-6 pb-3 md:pb-5 dark:border-gray-700 w-full">
                <!-- لغو -->
                <button type="button" wire:click="resetForm"
                    class="flex items-center justify-center gap-2 w-full sm:w-1/2 py-3 md:py-4 text-sm md:text-lg bg-[#B10909] text-white rounded-lg md:rounded-xl dark:bg-gray-700 dark:text-gray-200 transition">
                    <?php echo e(__('messages.cancel')); ?>

                </button>

                <!-- ذخیره / بروزرسانی -->
                <button type="submit"
                    class="flex items-center justify-center gap-2 w-full sm:w-1/2 py-3 md:py-4 text-sm md:text-lg bg-gradient-to-br from-indigo-400 to-indigo-500 text-white rounded-lg md:rounded-xl hover:bg-blue-700 transition">
                    <?php echo e($customerId ? __('messages.update') : __('messages.save')); ?>

                </button>
            </div>
        </form>
    </div>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/customers.blade.php ENDPATH**/ ?>