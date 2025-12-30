<div class="min-h-screen  dark:bg-black py-4 w-full p-6">
    <?php if(session()->has('message')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2B65E5] vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e(session('message')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?>

    <div class="w-full h-auto p-4 bg-[#F5F5F5] dark:border-white dark:border dark:bg-black rounded-2xl "
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <!-- هدر -->
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-black vazir dark:text-white tracking-widest">
                <?php echo e($customerId ? __('messages.title_edit') : __('messages.title_add')); ?>


            </h2>

            <p class="text-lg text-gray-600 dark:text-white mt-4 vazir">
                <?php echo e(__('messages.subtitle')); ?>

            </p>
        </div>

        <form wire:submit.prevent="saveCustomer" class="w-full">
            <!-- بخش آپلود تصاویر -->
            <div class="grid grid-cols-2  gap-4 md:gap-44 mb-6">
                <!-- عکس پروفایل -->
                <div class="flex flex-col items-center">
                    <label class="text-sm font-medium text-black dark:text-white mb-2">
                        <?php echo e(__('messages.profile_image')); ?>


                    </label>
                    <div class="relative w-20 h-20">
                        <?php if($newProfile): ?>
                        <img src="<?php echo e($newProfile->temporaryUrl()); ?>"
                            class="w-20 h-20 rounded-full object-cover border-2 border-blue-400">
                        <?php elseif($profile && $customerId): ?>
                        <img src="<?php echo e(asset('storage/' . $profile)); ?>"
                            class="w-20 h-20 rounded-full object-cover border-2 border-gray-300">
                        <?php else: ?>
                        <div class="w-20 h-20 rounded-full bg-[#2563EB] flex items-center justify-center">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/profile-circle.svg')); ?>" alt="">
                        </div>
                        <?php endif; ?>
                        <input type="file" wire:model="newProfile" accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                </div>

                <!-- عکس شناسنامه -->
                <div class="flex flex-col items-center">
                    <label class="text-sm font-medium text-gray-700 dark:text-white mb-2">
                        <?php echo e(__('messages.idcard_image')); ?>


                    </label>
                    <div class="relative w-20 h-20">
                        <?php if($newIdCardImage): ?>
                        <img src="<?php echo e($newIdCardImage->temporaryUrl()); ?>"
                            class="w-20 h-20 rounded-lg object-cover border-2 border-green-400">
                        <?php elseif($idCardImage && $customerId): ?>
                        <img src="<?php echo e(asset('storage/' . $idCardImage)); ?>"
                            class="w-20 h-20 rounded-lg object-cover border-2 border-gray-300">
                        <?php else: ?>
                        <div class="w-20 h-20 rounded-full bg-[#2563EB] flex items-center justify-center ">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/id.svg')); ?>" alt="">
                        </div>
                        <?php endif; ?>
                        <input type="file" wire:model="newIdCardImage" accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- فیلدهای اطلاعات -->
            <div class="space-y-4 w-full">
                <!-- ردیف 1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 w-full">
                    <div class="w-full">
                        <label class="block text-sm font-medium text-black vazir dark:text-white mb-2">
                            <?php echo e(__('messages.fullname')); ?>

                        </label>
                        <div class="relative w-full">
                            <input type="text" wire:model="fullname"
                                placeholder="<?php echo e(__('messages.placeholder_fullname')); ?> "
                                class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white p-3 rounded-xl border py-4 focus:ring-2 bg-transparent border-[#8C8C8C]  focus:border-none focus:ring-blue-500 focus:border-transparent   ">
                            <div class="absolute left-3 top-4 text-gray-400">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/profile.svg')); ?>" alt="">
                            </div>
                        </div>
                        <?php $__errorArgs = ['fullname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="w-full">
                        <label class="block text-sm font-medium text-black dark:text-white mb-2">
                            <?php echo e(__('messages.account_number')); ?>

                        </label>
                        <div class="flex gap-2 w-full">
                            <div class="relative flex-1">
                                <input type="text" wire:model.lazy="account"
                                    placeholder="<?php echo e(__('messages.placeholder_account')); ?> "
                                    class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white p-3 rounded-xl py-4 focus:ring-2 border bg-transparent border-[#8C8C8C] focus:ring-blue-500 focus:border-transparent "
                                    maxlength="16" <?php if(!$customerId): ?> <?php endif; ?>>
                                <div class="absolute left-3 top-4 text-gray-400">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/card.svg')); ?>" alt="">

                                </div>
                            </div>
                            <?php if(!$customerId): ?>
                            <button type="button" wire:click="generateNewAccountNumber"
                                class="px-4 py-3  border bg-transparent border-[#8C8C8C]    text-white rounded-lg transition">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/refresh-2.svg')); ?>" alt="">

                            </button>
                            <?php endif; ?>
                        </div>
                        <?php $__errorArgs = ['account'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- ردیف 2 -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 w-full">
                    <div class="w-full">
                        <label class="block text-sm font-medium text-black dark:text-white mb-2 vazir">
                            <?php echo e(__('messages.category')); ?>

                        </label>
                        <div class="relative w-full">
                            <select wire:model="category"
                                class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white p-3 rounded-xl py-4 border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 focus:border-transparent  appearance-none">
                                <option value=""><?php echo e(__('messages.choose')); ?></option>
                                <option value="<?php echo e(__('messages.category_normal')); ?>"><?php echo e(__('messages.category_normal')); ?></option>
                                <option value="<?php echo e(__('messages.category_regular')); ?>"><?php echo e(__('messages.category_regular')); ?></option>
                                <option value="gold"><?php echo e(__('messages.category_gold')); ?></option>
                                <option value="<?php echo e(__('messages.category_special')); ?>"><?php echo e(__('messages.category_special')); ?></option>
                            </select>
                            <div class="absolute left-3 top-4 text-gray-400">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/clipboard.svg')); ?>" alt="">
                            </div>

                        </div>
                        <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="w-full">
                        <label class="block text-sm font-medium text-black dark:text-white mb-2 vazir">
                            <?php echo e(__('messages.related_customer')); ?>

                        </label>

                        <div class="relative w-full">
                            <div x-data="{
                                    searchValue: '',
                                    selectedId: <?php if ((object) ('relatedCustomerId') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('relatedCustomerId'->value()); ?>')<?php echo e('relatedCustomerId'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('relatedCustomerId'); ?>')<?php endif; ?>,
                                    customers: <?php echo \Illuminate\Support\Js::from($relatedCustomers)->toHtml() ?>,

                                    handleSelect(event) {
                                        const selected = this.customers.find(
                                            c => event.target.value === `${c.account_number} - ${c.fullname}`
                                        );
                                        if (selected) {
                                            this.selectedId = selected.id;
                                            this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                            // فراخوانی متد Livewire برای انتخاب مشتری
                                            $wire.selectRelatedCustomer(selected.id);
                                        } else {
                                            // اگر چیزی اشتباه وارد شد، مقدار پاک شود
                                            this.selectedId = null;
                                            this.searchValue = '';
                                            $wire.set('relatedCustomerId', null);
                                        }
                                    },

                                    updateDisplay() {
                                        const selected = this.customers.find(c => c.id === this.selectedId);
                                        this.searchValue = selected ? `${selected.account_number} - ${selected.fullname}` : '';
                                    },

                                    clearSelection() {
                                        this.selectedId = null;
                                        this.searchValue = '';
                                        $wire.set('relatedCustomerId', null);
                                    }
                                }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())" class="relative w-full">

                                <!-- فیلد جستجو -->
                                <input type="text" list="relatedCustomersList" x-model="searchValue"
                                    @change="handleSelect"
                                    placeholder="<?php echo e(__('messages.search_customer_placeholder')); ?>"
                                    class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white p-3 rounded-xl py-4 border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 focus:border-transparent  pr-10"
                                    autocomplete="off">

                                <!-- دیتالیست برای گزینه‌ها -->
                                <datalist id="relatedCustomersList">
                                    <?php $__currentLoopData = $relatedCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                        <?php echo e($customer['fullname']); ?> (<?php echo e($customer['phone']); ?>)
                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </datalist>

                                <!-- آیکون dropdown -->
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"
                                            stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M3.40991 22C3.40991 18.13 7.25994 15 11.9999 15" stroke="#292D32"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M18.2 21.4C19.9673 21.4 21.4 19.9673 21.4 18.2C21.4 16.4327 19.9673 15 18.2 15C16.4327 15 15 16.4327 15 18.2C15 19.9673 16.4327 21.4 18.2 21.4Z"
                                            stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M22 22L21 21" stroke="#292D32" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>

                                <!-- دکمه پاک کردن (فقط وقتی مقداری انتخاب شده باشد) -->
                                <template x-if="selectedId">
                                    <button type="button" @click="clearSelection()"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <?php $__errorArgs = ['relatedCustomerId'];
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
                </div>

                <div class="w-full">
                    <label class="block text-sm font-medium text-black dark:text-white mb-2 vazir">
                        <?php echo e(__('messages.city')); ?>


                    </label>
                    <div class="relative w-full">
                        <input type="text" wire:model="city" placeholder="<?php echo e(__('messages.placeholder_city')); ?> "
                            class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white p-3 rounded-xl py-4 focus:ring-2 border bg-transparent border-[#8C8C8C] focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute left-3 top-4 text-gray-400">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/Group.svg')); ?>" alt="">
                        </div>
                    </div>
                    <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- ردیف 3 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 w-full">
                <div class="w-full">
                    <label class="block text-sm font-medium text-black vazir dark:text-white mb-2">
                        <?php echo e(__('messages.phone')); ?>

                    </label>
                    <div class="relative w-full">
                        <input type="text" wire:model.lazy="phone" placeholder="<?php echo e(__('messages.placeholder_phone')); ?> "
                            class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white p-3 rounded-xl py-4 focus:ring-2 border bg-transparent border-[#8C8C8C] focus:ring-blue-500 focus:border-transparent ">
                        <div class="absolute left-3 top-4 text-gray-400">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/call.svg')); ?>" alt="">

                        </div>
                    </div>
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="w-full">
                    <label class="block text-sm font-medium text-black vazir dark:text-white mb-2">
                        <?php echo e(__('messages.tazkira')); ?>


                    </label>
                    <div class="relative w-full">
                        <input type="text" wire:model.lazy="tazkira"
                            placeholder="<?php echo e(__('messages.placeholder_tazkira')); ?> "
                            class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white p-3 rounded-xl py-4 focus:ring-2 border bg-transparent border-[#8C8C8C] focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute left-3 top-4 text-gray-400">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/qlementine-icons_id-card-16.svg')); ?>" alt="">
                        </div>
                    </div>
                    <?php $__errorArgs = ['tazkira'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- ردیف 4 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 w-full">
                <div class="w-full">
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                        <?php echo e(__('messages.whatsapp')); ?>

                    </label>
                    <div class="relative w-full">
                        <input type="text" wire:model.lazy="whatsapp"
                            placeholder="<?php echo e(__('messages.placeholder_whatsapp')); ?> "
                            class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white p-3 rounded-xl py-4 focus:ring-2 border bg-transparent border-[#8C8C8C] focus:ring-blue-500 focus:border-transparent ">
                        <div class="absolute left-3 top-4 text-green-500">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/Vector.svg')); ?>" alt="">
                        </div>
                    </div>
                    <?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="w-full">
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                        <?php echo e(__('messages.password')); ?>

                    </label>
                    <div class="relative w-full">
                        <input type="password" wire:model="password"
                            placeholder="<?php echo e(__('messages.placeholder_password')); ?> "
                            class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white p-3 rounded-xl py-4 focus:ring-2 border bg-transparent border-[#8C8C8C] focus:ring-blue-500 focus:border-transparent ">
                        <div class="absolute left-3 top-4 text-gray-400">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/lock.svg')); ?>" alt="">
                        </div>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
              <!-- دکمه‌های اقدام -->
    <div class="flex justify-center gap-4 mt-8 pt-6 pb-5  dark:border-gray-700 w-full">
        <!-- لغو -->
        <button type="button" wire:click="resetForm"
            class="flex items-center justify-center gap-2 w-1/2 py-4 text-sm bg-[#B10909] text-white rounded-xl dark:bg-[#B10909] dark:text-gray-200 transition">
            <?php echo e(__('messages.cancel')); ?>


        </button>

        <!-- ذخیره / بروزرسانی -->
        <button type="submit"
            class="flex items-center justify-center gap-2 w-1/2 py-4 text-sm bg-[#2563EB] text-white rounded-xl hover:bg-blue-700 transition">
            <?php echo e($customerId ? __('messages.update') : __('messages.save')); ?>

        </button>
    </div>

    </div>

  
    </form>

    <style>
        .scroll-container {
            scrollbar-width: thin;
            scrollbar-color: #e5e7eb #f9fafb;
        }

        .scroll-container::-webkit-scrollbar {
            height: 6px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: #f9fafb;
            border-radius: 10px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        #selectCustomer {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: transparent;
            padding-left: 1rem;
        }

        input[list]::-webkit-calendar-picker-indicator {
            display: none !important;
            -webkit-appearance: none;
        }

        /* در Firefox */
        input[list]::-moz-list-button {
            display: none !important;
        }

        /* در Edge جدید */  
        input[list]::-ms-clear,
        input[list]::-ms-expand {
            display: none !important;
        }
    </style>
</div>



</div>
<?php $__env->startPush('script'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php $__env->stopPush(); ?><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/customers.blade.php ENDPATH**/ ?>