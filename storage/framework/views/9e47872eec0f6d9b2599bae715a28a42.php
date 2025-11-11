<div>
    <div class="container mx-auto px-4">
        <!-- Session Message -->
        <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2B65E5] vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('message')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Page Header -->
        <div class="space-y-4 mb-6">
            <h1 class="text-[24px] font-medium vazir">ثبت احواله ها</h1>
            <h1 class="text-[#8C8C8C]">صفحه ثبت و ویراش احواله های بانکی</h1>
        </div>
        
        <hr class="my-6 border-t border-[#D9D9D9] w-full">

        <!-- Main Content - Form and Table -->
        <div class="flex flex-col lg:flex-row gap-10 mt-4">

            <!-- Transaction Form -->
            <div class="flex flex-col bg-[#F5F5F5] w-full lg:w-[574px] p-[12px] h-auto rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <!-- Form Header -->
                <div class="flex flex-row justify-between p-[10px] border border-[#8C8C8C] rounded-[12px] flex-wrap">
                    <p class="flex justify-center items-center text-center">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/edit-2.svg')); ?>" alt="" class="h-6 w-6">
                        <?php echo e($transactionId ? 'فورم ویرایش حواله' : 'فورم ثبت  اطلاعات  حواله'); ?>

                    </p>

                 
                </div>

                <!-- Form -->
                <form wire:submit.prevent="submitTransaction">
                    <!-- Account Number and Currency -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- Account Number -->
                        <div class="flex-1">
                            <div class="relative w-full">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">نمبرحساب مشتری</label>
                                <div x-data="{
                                    searchValue: '',
                                    selectedId: <?php if ((object) ('selectedAccount') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'->value()); ?>')<?php echo e('selectedAccount'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'); ?>')<?php endif; ?>,
                                    customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
                                    handleSelect(event) {
                                        const selected = this.customers.find(
                                            c => event.target.value === `${c.account_number} - ${c.fullname}`
                                        );
                                        if (selected) {
                                            this.selectedId = selected.id;
                                            this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                            $wire.selectCustomer(selected.id);
                                            $wire.set('search', selected.fullname);
                                        } else {
                                            this.selectedId = null;
                                            this.searchValue = '';
                                            $wire.set('selectedAccount', null);
                                            $wire.set('search', '');
                                        }
                                    },
                                    updateDisplay() {
                                        const selected = this.customers.find(c => c.id === this.selectedId);
                                        this.searchValue = selected ? `${selected.account_number} - ${selected.fullname}` : '';
                                    }
                                }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())"
                                    class="relative w-full">
                                    <input list="customersList" x-model="searchValue" @change="handleSelect"
                                        placeholder="جستجو یا انتخاب حساب..."
                                        class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                        autocomplete="off">
                                    <datalist id="customersList">
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </datalist>
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓">
                                    </div>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedAccount'];
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

                        <!-- Currency Type -->
                        <div class="lg:w-[191px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نوع ارز</label>
                            <div class="relative w-full">
                                <select wire:model="currency"
                                    class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                                    <option value="">انتخاب ارز</option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c['code']); ?>"><?php echo e($c['name_fa']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓" class="w-4 h-4">
                                </div>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['currency'];
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

                    <!-- Amount and Date -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- Amount -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مقدار</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>
                            <!--[if BLOCK]><![endif]--><?php if($amountInWords): ?>
                            <p class="text-sm text-blue-600 mt-2 vazir"><?php echo e($amountInWords); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['amount'];
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

                        <!-- Date -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                            <input type="text" id="datePicker" wire:model="date" wire:ignore placeholder="YYYY/MM/DD"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        </div>
                    </div>

                    <!-- Time and Tracking Code -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- Time -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">ساعت</label>
                            <input type="text" id="datePicker" wire:model="clock" wire:ignore placeholder="2:25:20"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        </div>

                        <!-- Tracking Code -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">کد رهگیری</label>
                            <input type="text" id="datePicker" wire:model="code" wire:ignore placeholder="5155221034568"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        </div>
                    </div>

                    <!-- Source and Destination Banks -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- Source Bank -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">بانک مبدا</label>
                            <input type="text" id="datePicker" wire:model="from_bank" wire:ignore placeholder="سپه"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        </div>

                        <!-- Destination Bank -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">بانک مقصد</label>
                            <input type="text" id="datePicker" wire:model="to_bank" wire:ignore placeholder="صادرات"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        </div>
                    </div>

                    <!-- Zone and Beneficiary -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- Zone -->
                        <div class="lg:w-[250px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">درج زون ها</label>
                            <div class="relative">
                                <select wire:model="zone" wire:init="setDefaultZone"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none"
                                    style="max-height: 200px; overflow-y: auto;">
                                    <option value="">انتخاب زون</option>
                                    <option value="<?php echo e(Auth::guard('sarafi')->user()->zone); ?>">
                                        <?php echo e(Auth::guard('sarafi')->user()->zone); ?>

                                    </option>
                                </select>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['zone'];
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

                        <!-- Beneficiary Name -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir"> نام کارت گیرنده</label>
                            <input type="text" id="datePicker" wire:model="giver_name" wire:ignore placeholder="مجید مرتضی"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        </div>
                    </div>

                    <!-- Source and Destination Account Numbers -->
                    <div class="flex-1 flex gap-2 mt-2">
                     
                          <div class="lg:w-[440px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">شماره کارت مقصد</label>
                            <input type="text" id="datePicker" wire:model="giver_name" value="xxxx -xxxx-xxxx-1742" wire:ignore placeholder="ٓٓٓxxxx -xxxx-xxxx-1742"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        </div>
                        <!-- Destination Account -->
                        <div class="relative w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نمبرحساب مقصد</label>
                            <div x-data="{
                                searchValue: '',
                                selectedId: <?php if ((object) ('selectedAccount') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'->value()); ?>')<?php echo e('selectedAccount'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'); ?>')<?php endif; ?>,
                                customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
                                handleSelect(event) {
                                    const selected = this.customers.find(
                                        c => event.target.value === `${c.account_number} - ${c.fullname}`
                                    );
                                    if (selected) {
                                        this.selectedId = selected.id;
                                        this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                        $wire.selectCustomer(selected.id);
                                        $wire.set('search', selected.fullname);
                                    } else {
                                        this.selectedId = null;
                                        this.searchValue = '';
                                        $wire.set('selectedAccount', null);
                                        $wire.set('search', '');
                                    }
                                },
                                updateDisplay() {
                                    const selected = this.customers.find(c => c.id === this.selectedId);
                                    this.searchValue = selected ? `${selected.account_number} - ${selected.fullname}` : '';
                                }
                            }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())"
                                class="relative w-full">
                                <input list="customersList" x-model="searchValue" @change="handleSelect"
                                    placeholder="جستجو یا انتخاب حساب..."
                                    class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="customersList">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </datalist>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓">
                                </div>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedAccount'];
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

                    <!-- Transaction Description -->
                    <div class="mt-3 flex gap-3">
                        <div class="w-full">
                            <textarea wire:model="description" rows="3" placeholder="شرح حواله..."
                                class="w-full p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white resize-none"></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['description'];
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

                    <!-- File Upload -->
                    <div class="mt-2 flex gap-3">
                        <div class="w-full">
                            <div x-data="{ files: [] }"
                                x-on:drop.prevent="files = $event.dataTransfer.files; $wire.upload('file', files[0])"
                                x-on:dragover.prevent
                                class="w-full h-[150px] p-3 rounded-[12px] border border-dashed focus:ring-2 bg-white border-[#112080] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white flex flex-col justify-center items-center text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition"
                                x-on:click="$refs.fileInput.click()">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/upload.svg')); ?>" alt="آپلود" class="w-12 h-12 mb-2">
                                <h1 class="font-vazir text-gray-600 dark:text-gray-300 text-[16px]">فایل را اینجا وارد کنید یا بکشید</h1>
                                <input type="file" class="hidden" x-ref="fileInput"
                                    x-on:change="$wire.upload('file', $event.target.files[0])">
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['file'];
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

                    <!-- Action Buttons -->
                    <div class="flex gap-4 p-4 justify-center items-center text-center flex-wrap">
                        <button type="submit"
                            class="bg-[#61B138] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">
                            <?php echo e($transactionId ? 'بروزرسانی' : 'ثبت'); ?>

                        </button>

                        <!--[if BLOCK]><![endif]--><?php if(!$transactionId): ?>
                        <button type="button" wire:click="submitAndPrint"
                            class="bg-[#2563EB] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">
                            ثبت و چاپ
                        </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <button type="button" wire:click="cancel"
                            class="bg-[#DD2424] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">
                            <?php echo e($transactionId ? 'لغو ویرایش' : 'انصراف'); ?>

                        </button>
                    </div>
                </form>
            </div>

            <!-- Transactions Table -->
            <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px]"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <!-- Table Header -->
                <div class="flex flex-col md:flex-row justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">حواله های ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <!-- Selected Customer Filter -->
                        <!--[if BLOCK]><![endif]--><?php if($selectedCustomerId): ?>
                        <?php
                        $selectedCustomer = \App\Models\Sarafi\Customer::find($selectedCustomerId);
                        ?>
                        <div class="bg-blue-100 px-3 py-2 rounded-lg flex items-center gap-2">
                            <span class="text-blue-700 vazir">فیلتر: <?php echo e($selectedCustomer->fullname ?? ''); ?></span>
                            <button wire:click="clearFilter" class="text-red-500 hover:text-red-700 text-lg">
                                ✕
                            </button>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!-- Search Box -->
                        <div class="relative w-full md:w-[302px]">
                            <input type="text" wire:model.live="search"
                                class="border border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                                placeholder="جستجو بر اساس نام یا نمبر حساب...">

                            <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">

                            <!--[if BLOCK]><![endif]--><?php if($search): ?>
                            <button wire:click="clearSearchAndFilter"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <!--[if BLOCK]><![endif]--><?php if($search && count($filteredCustomers) > 0 && !$selectedCustomerId): ?>
                            <ul class="absolute z-50 w-full bg-white border border-gray-300 mt-1 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $filteredCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li wire:click="selectCustomer(<?php echo e($customer->id); ?>)"
                                    class="px-3 py-2 hover:bg-blue-100 cursor-pointer flex justify-between items-center">
                                    <span><?php echo e($customer->fullname); ?></span>
                                    <span class="text-gray-500 text-sm"><?php echo e($customer->account_number); ?></span>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </ul>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                        <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="bg-[#2B65E5] dark:bg-gray-700 text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
                                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                                <tr>
                                    <th class="px-4 py-4 font-bold w-16">#</th>
                                    <th class="px-4 py-4 font-bold w-48">نام مشتری</th>
                                    <th class="px-4 py-4 font-bold w-32">به کارت</th>
                                    <th class="px-4 py-4 font-bold w-40">مبلغ</th>
                                    <th class="px-4 py-4 font-bold w-32">واحد</th>
                                    <th class="px-4 py-4 font-bold w-80 text-center">توضیحات</th>
                                    <th class="px-4 py-4 font-bold w-40">تاریخ</th>
                                    <th class="px-4 py-4 font-bold w-48 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        <?php echo e($key + 1); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        <?php echo e($transaction->customer->fullname ?? '-'); ?>

                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        <span class="px-3 py-1 rounded-full text-[16px] <?php echo e($transaction->type === 'رسید' ? ' text-green-800' : 'text-red-800'); ?>">
                                            <?php echo e($transaction->type); ?>

                                        </span>
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        <span class="px-3 py-1 rounded-full text-[16px] <?php echo e($transaction->type === 'نقدی' ? ' text-green-800' : 'text-red-800'); ?>">
                                            <?php echo e($transaction->account_type); ?>

                                        </span>
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-40">
                                        <?php echo e(number_format($transaction->amount)); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        <?php echo e(collect($currencies)->firstWhere('code', $transaction->currency)['name_fa'] ?? $transaction->currency); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <div class="space-y-1 text-right">
                                            <p class="text-sm">توسط: <?php echo e($transaction->by); ?></p>
                                            <p class="text-sm">زون: <?php echo e($transaction->zone); ?></p>
                                            <p class="text-sm">تفصیلات: <?php echo e($transaction->description); ?></p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium">
                                                <?php echo e(explode(' ', $transaction->date)[0]); ?>

                                            </div>
                                            <div class="text-gray-500 text-sm mt-1">
                                                <?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('h:i A')); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- Edit Button -->
                                            <button wire:click="edit(<?php echo e($transaction->id); ?>)" class="w-12 h-12 flex items-center justify-center rounded-full transition-colors" title="ویرایش">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>" class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- Delete Button -->
                                            <button wire:click="confirmDelete(<?php echo e($transaction->id); ?>)"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="حذف">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>" class="w-8 h-8" alt="Delete">
                                            </button>

                                            <!-- Print Button -->
                                            <button wire:click="print(<?php echo e($transaction->id); ?>)" class="w-12 h-12 flex items-center justify-center rounded-full transition-colors" title="پرینت">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>" class="w-10 h-10" alt="Print">
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-gray-500 py-8 text-lg">
                                        <!--[if BLOCK]><![endif]--><?php if($selectedCustomerId): ?>
                                        هیچ حواله برای این مشتری یافت نشد
                                        <?php else: ?>
                                        هیچ حواله ای یافت نشد
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                </tr>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <!--[if BLOCK]><![endif]--><?php if($confirmDeleteId): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-10 z-50">
        <div class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] rounded-[12px] shadow-xl w-[653px] h-[239.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
            <button wire:click="$set('confirmDeleteId', null)" class="flex right-0 h-4 w-4">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>" alt="">
            </button>
            <h1 class="text-2xl text-black shabnam font-medium leading-[100%]">حذف حواله</h1>
            <hr class="bg-[#E1DED3] mt-8">
            <p class="mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این حواله را حذف کنید؟</p>
            <div class="flex justify-center gap-4">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-20 text-white text-xl shabnam-fd py-3 bg-[#DD2424] rounded-xl transition">
                    <?php echo e(__('messages.no')); ?>

                </button>
                <button wire:click="deleteConfirmed"
                    class="px-20 py-3 bg-[#2563EB] text-xl shabnam-fd text-white rounded-xl transition flex items-center gap-2">
                    <?php echo e(__('messages.yes')); ?>

                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>

<!-- Event Alert -->
<?php $__env->startPush('script'); ?>
    <script>
    window.addEventListener('report-alert', event => {
        alert(event.detail.message);
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('style'); ?>
    
<!-- Scrollbar Style -->
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

    input[list]::-moz-list-button {
        display: none !important;
    }

    input[list]::-ms-clear,
    input[list]::-ms-expand {
        display: none !important;
    }
</style>
<?php $__env->stopPush(); ?><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/remittance.blade.php ENDPATH**/ ?>