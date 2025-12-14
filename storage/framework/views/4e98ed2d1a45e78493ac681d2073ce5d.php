<div>
    <div class="container mx-auto">
        <!-- پیام‌های سیستم -->
        <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2563EB] azir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('message')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <?php if(session()->has('error')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-red-700 vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('error')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- هدر صفحه -->
        <div class="space-y-4 mb-6">
            <h1 class="text-[24px] font-medium vazir">ثبت ارسال پول به صرافی دیگر</h1>
            <h1 class="text-[#8C8C8C]">صفحه ثبت و ویرایش ارسال پول به مشتریان در صرافی دیگر</h1>
        </div>

        <!-- کارت‌های موجودی -->
        <div class="scroll-container overflow-x-auto whitespace-nowrap py-3">
            <!-- کارت مشتری انتخاب شده -->
            <!--[if BLOCK]><![endif]--><?php if($selectedCustomer): ?>
            <div class="inline-block align-top ml-4 h-auto">
                <div
                    class="flex flex-col h-[180px] w-[273px] pr-5 pl-5 pt-2 rounded-[12px] bg-gradient-to-b from-[#20559c] to-[#3065b5] text-white">
                    <!-- عکس مشتری -->
                    <div x-data="{ showLargeImage: false, largeImageSrc: '' }">
                        <!--[if BLOCK]><![endif]--><?php if($selectedCustomer->image): ?>
                        <div class="flex justify-center mb-2">
                            <img src="<?php echo e(Storage::url($selectedCustomer->image)); ?>"
                                alt="<?php echo e($selectedCustomer->fullname); ?>"
                                class="w-20 h-20 rounded-lg object-cover border-2 border-white cursor-pointer hover:scale-105 transition-transform"
                                @click="showLargeImage = true; largeImageSrc = '<?php echo e(Storage::url($selectedCustomer->image)); ?>'">
                        </div>
                        <?php else: ?>
                        <div class="flex justify-center mb-2">
                            <img src="<?php echo e(asset('assets/web.jpg')); ?>" alt="<?php echo e($selectedCustomer->fullname); ?>"
                                class="w-20 h-20 rounded-lg object-cover border-2 border-white cursor-pointer hover:scale-105 transition-transform"
                                @click="showLargeImage = true; largeImageSrc = '<?php echo e(asset('assets/web.jpg')); ?>'">
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- نام مشتری -->
                    <h1 class="text-[20px] text-white text-center font-bold truncate"
                        title="<?php echo e($selectedCustomer->fullname); ?>">
                        <?php echo e($selectedCustomer->fullname); ?>

                    </h1>

                    <!-- شماره تماس -->
                    <!--[if BLOCK]><![endif]--><?php if($selectedCustomer->phone): ?>
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.5-5.2-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1zM5 6h1.5c.1 1.2.3 2.4.6 3.5L5.3 11.8c-.9-2-1.3-4.1-1.3-6.2V6zM19 19c-2.1 0-4.2-.4-6.2-1.3l2.3-2.3c1.1.3 2.3.5 3.5.6V19z" />
                        </svg>
                        <span class="text-white text-[14px] dir-ltr text-left"><?php echo e($selectedCustomer->phone); ?></span>
                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!-- شماره حساب -->
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8h16v10zm-8-7c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-2.2 0-4 1.8-4 4h8c0-2.2-1.8-4-4-4z" />
                        </svg>
                        <span class="text-white text-[14px] dir-ltr text-left"><?php echo e($selectedCustomer->account_number); ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- کارت‌های موجودی ارزها -->
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            $currencyName = $currencyItem['name_fa'];
            $cashBalance = $customerCashBalances[$currencyName] ?? 0;
            $bankBalance = $customerBankBalances[$currencyName] ?? 0;
            $totalBalance = $customerTotalBalances[$currencyName] ?? 0;
            ?>
            <div class="inline-block align-top ml-4 h-auto">
                <div
                    class="flex flex-col h-[180px] w-[273px] pr-5 pl-5 pt-3 rounded-[12px] bg-gradient-to-b from-[#2563EB] to-[#5474BB] text-white">
                    <h1 class="text-[24px] text-white"><?php echo e($currencyName); ?></h1>
                    <div class="flex flex-col gap-1 mt-1">
                        <div class="flex justify-between items-center text-[14px]">
                            <span>نقدی:</span>
                            <span class="font-bold" dir="ltr"><?php echo e(number_format($cashBalance)); ?></span>
                        </div>
                        <div class="flex justify-between items-center text-[14px]">
                            <span>بانکی:</span>
                            <span class="font-bold" dir="ltr"><?php echo e(number_format($bankBalance)); ?></span>
                        </div>
                        <div class="flex justify-between items-center text-[14px] border-t border-white/30 pt-1">
                            <span class="font-semibold">مجموعه:</span>
                            <span class="font-bold text-[16px]" dir="ltr"><?php echo e(number_format($totalBalance)); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <hr class="my-6 border-t border-[#D9D9D9] w-full">

        <!-- محتوای اصلی - فرم و جدول -->
        <div class="flex flex-col lg:flex-row gap-5 mt-4 mx-auto">
            <!-- فرم ثبت ارسال -->
            <div class="flex flex-col bg-[#F5F5F5] w-[420px] lg:w-[534px] mx-auto p-[12px] h-fit rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040;">
                <!-- هدر فرم -->
                <div class="flex flex-row gap-4 p-4 border border-[#8C8C8C] rounded-[12px] flex-wrap items-center justify-between">
                    <div class="flex">
                          <img src="<?php echo e(asset('assets/sarafi/all_icon/edit-2.svg')); ?>" alt="" class="h-6 w-6">
                    <p class="text-center">
                        <!--[if BLOCK]><![endif]--><?php if($isEditMode): ?>
                        ویرایش ارسال
                        <?php else: ?>
                        فورم ثبت ارسال
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </p>
                    </div>
                        <button wire:click="toggleAccountType" class="rounded-[8px] p-[10px] text-white vazir px-12 font-semibold transition-colors duration-500 ease-in-out
                        <?php echo e($accountType === 'نقدی' ? 'bg-[#2563EB]' : 'bg-[#DD2424]'); ?>">
                        <?php echo e($accountType === 'نقدی' ? 'نقدی' : 'بانکی'); ?>

                    </button>
                </div>

                <!-- فرم -->
                <form wire:submit.prevent="submitRemittance">
                    <!-- حساب مشتری و صرافی مقصد -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- حساب مشتری فرستنده -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">از حساب مشتری</label>
                            <div x-data="{
                searchValue: <?php if ((object) ('search') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('search'->value()); ?>')<?php echo e('search'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('search'); ?>')<?php endif; ?>,
                customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
                handleSelect(event) {
                    const value = event.target.value;
                    const match = value.match(/(\d+) - (.+)/);
                    if (match) {
                        const accountNumber = match[1];
                        const fullname = match[2];
                        const customer = this.customers.find(c => 
                            c.account_number == accountNumber && 
                            c.fullname == fullname
                        );
                        if (customer) {
                            $wire.selectCustomer(customer.id);
                        }
                    }
                }
            }" class="relative w-full">
                                <input list="fromCustomersList" x-model="searchValue" @change="handleSelect"
                                    placeholder="جستجو یا انتخاب حساب فرستنده..."
                                    class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="fromCustomersList">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer->account_number); ?> - <?php echo e($customer->fullname); ?>">
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

                        <!-- حساب مشتری گیرنده -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">به حساب مشتری</label>
                            <div x-data="{
                searchValue: <?php if ((object) ('to_customer_search') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('to_customer_search'->value()); ?>')<?php echo e('to_customer_search'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('to_customer_search'); ?>')<?php endif; ?>,
                customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
                handleSelect(event) {
                    const value = event.target.value;
                    const match = value.match(/(\d+) - (.+)/);
                    if (match) {
                        const accountNumber = match[1];
                        const fullname = match[2];
                        const customer = this.customers.find(c => 
                            c.account_number == accountNumber && 
                            c.fullname == fullname
                        );
                        if (customer) {
                            $wire.selectToCustomer(customer.id);
                        }
                    }
                }
            }" class="relative w-full">
                                <input list="toCustomersList" x-model="searchValue" @change="handleSelect"
                                    placeholder="جستجو یا انتخاب حساب گیرنده..."
                                    class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="toCustomersList">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer->account_number); ?> - <?php echo e($customer->fullname); ?>">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </datalist>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓">
                                </div>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['to_customer_id'];
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

                    <!-- صرافی مقصد -->
                    <div class="mt-2">
                        <label class="block text-[16px] font-medium text-black mb-1 vazir">به صرافی</label>
                        <div class="relative w-full">
                            <select wire:model="to_sarafi"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="">انتخاب صرافی مقصد</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sarafi_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sarafi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sarafi->id); ?>">
                                    <?php echo e($sarafi->sarafi_name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓">
                            </div>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['to_sarafi'];
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

                    <!-- مقدار و نوع ارز -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- مقدار -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مقدار</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500   dark:text-white"
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


                        <!-- نوع ارز -->
                        <div class="lg:w-[191px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نوع ارز</label>
                            <div class="relative w-full">
                                <select wire:model="currency"
                                    class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
                                    <option value="">انتخاب ارز</option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c['code']); ?>"><?php echo e($c['name_fa']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓"
                                        class="w-4 h-4">
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

                    <!-- تاریخ و زون -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- تاریخ -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                            <input type="text" wire:model="date" placeholder="YYYY/MM/DD"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['date'];
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

                        <!-- زون -->
                        <div class="lg:w-[250px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">زون</label>
                            <div class="relative">
                                <select wire:model="zone"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 appearance-none">
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
                    </div>


                    <!-- توضیحات -->
                    <div class="mt-3">
                        <textarea wire:model="description" rows="3" placeholder="شرح ارسال ..."
                            class="w-full p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 resize-none"></textarea>
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

                    <!-- دکمه‌های عملیات -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 py-4 justify-center items-center text-center">
                        <button type="submit"
                            class="bg-[#61B138] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">
                            <!--[if BLOCK]><![endif]--><?php if($isEditMode): ?>
                            بروزرسانی
                            <?php else: ?>
                            ثبت
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </button>

                        <!--[if BLOCK]><![endif]--><?php if(!$isEditMode): ?>
                        <button type="button" wire:click="submitAndPrint"
                            class="bg-[#2563EB] text-[14px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">
                            ثبت و چاپ
                        </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <button type="button" wire:click="cancel"
                            class="bg-[#DD2424] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">
                            انصراف
                        </button>
                    </div>
                </form>
            </div>

            <!-- جدول تراکنش‌ها -->
            <div class="flex-1 flex flex-col bg-[#F5F5F5] p-4 lg:p-6 rounded-[12px] w-full"
                style="box-shadow: 0px 4px 4px 0px #00000040;">
                <!-- هدر جدول -->
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 justify-between items-center border border-[#8C8C8C] p-4 rounded-[12px] mb-4 gap-4">
                    <h1 class="text-xl lg:text-2xl vazir">تراکنش‌های ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <!-- فیلتر مشتری -->
                        <!--[if BLOCK]><![endif]--><?php if($selectedCustomerId && $selectedCustomer): ?>
                        <div class="bg-blue-100 px-3 py-2 rounded-lg flex items-center gap-2">
                            <span class="text-blue-700 vazir">فیلتر: <?php echo e($selectedCustomer->fullname); ?></span>
                            <button wire:click="clearFilter" class="text-red-500 hover:text-red-700 text-lg">
                                ✕
                            </button>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!-- جستجو -->
                        <div class="relative flex-1">
                            <input type="text" wire:model.live="search"
                                class="border border-[#8C8C8C] w-full h-12 bg-transparent rounded-[12px] p-3 pr-10"
                                placeholder="جستجو بر اساس نام یا شماره حساب...">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5">
                            <!--[if BLOCK]><![endif]--><?php if($search): ?>
                            <button wire:click="clearSearch"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>

                <!-- لیست نتایج جستجو -->
                <!--[if BLOCK]><![endif]--><?php if($search && count($filteredCustomers) > 0 && !$selectedCustomerId): ?>
                <div class="mb-4 border border-gray-300 rounded-md shadow-lg bg-white">
                    <ul class="max-h-60 overflow-y-auto">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $filteredCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li wire:click="selectCustomer(<?php echo e($customer->id); ?>)"
                            class="px-4 py-3 hover:bg-blue-100 cursor-pointer flex justify-between items-center border-b border-gray-100 last:border-b-0">
                            <div>
                                <span class="font-medium"><?php echo e($customer->fullname); ?></span>
                                <span class="text-gray-500 text-sm mr-2"><?php echo e($customer->account_number); ?></span>
                            </div>
                            <span class="text-blue-500 text-sm">انتخاب</span>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </ul>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!-- جدول -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500">
                        <thead class="bg-[#2B65E5] text-white text-[16px] vazir h-[67px] sticky top-0">
                            <tr>
                                <th class="px-4 py-4 font-bold">#</th>
                                <th class="px-4 py-4 font-bold">مشتری</th>
                                <th class="px-4 py-4 font-bold">صرافی</th>
                                <th class="px-4 py-4 font-bold">مبلغ</th>
                                <th class="px-4 py-4 font-bold">واحد</th>
                                <th class="px-4 py-4 font-bold text-center">توضیحات</th>
                                <th class="px-4 py-4 font-bold">تاریخ</th>
                                <th class="px-4 py-4 font-bold text-center">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="text-black border-b border-[#D9D9D9] bg-transparent hover:bg-gray-50">
                                <td class="px-4 py-4 vazir text-[14px] font-medium">
                                    <?php echo e($loop->iteration); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[14px] font-medium">
                                    <?php echo e($transaction->customer->fullname ?? '-'); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[14px] font-medium">
                                    <?php echo e($this->getSarafiName($transaction)); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[14px] font-medium">
                                    <span
                                        class="<?php echo e($transaction->type === 'برداشت' ? 'text-red-600' : 'text-black'); ?>">
                                        <?php echo e(number_format($transaction->amount)); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] font-medium">
                                    <?php echo e(collect($currencies)->firstWhere('code', $transaction->currency)['name_fa'] ??
                                    $transaction->currency); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[14px] font-medium text-center">
                                    <div class="space-y-1 text-right">
                                        <p class="text-sm">زون: <?php echo e($transaction->zone); ?></p>
                                        <!--[if BLOCK]><![endif]--><?php if($transaction->description): ?>
                                        <p class="text-sm">شرح: <?php echo e($transaction->description); ?></p>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <p class="text-sm">
                                            <!--[if BLOCK]><![endif]--><?php if($transaction->type === 'برداشت'): ?>
                                            برای: <?php echo e($this->getOtherCustomerName($transaction)); ?>

                                            <?php else: ?>
                                           از حساب : <?php echo e($this->getOtherCustomerName($transaction)); ?>

                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] text-center">
                                    <div>
                                        <div class="font-medium">
                                            <?php echo e($transaction->date); ?>

                                        </div>
                                        <div class="text-gray-500 text-sm mt-1">
                                            <?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('H:i')); ?>

                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="edit(<?php echo e($transaction->id); ?>)"
                                            class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 hover:bg-blue-200 transition-colors"
                                            title="ویرایش">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>"
                                                class="w-5 h-5" alt="Edit">
                                        </button>

                                        <button wire:click="confirmDelete(<?php echo e($transaction->id); ?>)"
                                            class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 hover:bg-red-200 transition-colors"
                                            title="حذف">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>"
                                                class="w-5 h-5" alt="Delete">
                                        </button>

                                        <button wire:click="print(<?php echo e($transaction->id); ?>)"
                                            class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 hover:bg-green-200 transition-colors"
                                            title="پرینت">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>"
                                                class="w-5 h-5" alt="Print">
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center text-gray-500 py-8 text-lg">
                                    <!--[if BLOCK]><![endif]--><?php if($selectedCustomerId): ?>
                                    هیچ تراکنشی برای این مشتری یافت نشد
                                    <?php else: ?>
                                    هیچ تراکنشی یافت نشد
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- مودال تأیید حذف -->
        <!--[if BLOCK]><![endif]--><?php if($confirmDeleteId): ?>
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6 text-center animate-fadeIn">
                <h2 class="text-2xl text-black font-medium mb-4">حذف تراکنش</h2>
                <p class="text-gray-600 mb-6">آیا مطمئن هستید می‌خواهید این تراکنش را حذف کنید؟</p>
                <div class="flex justify-center gap-3">
                    <button wire:click="$set('confirmDeleteId', null)"
                        class="px-8 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        انصراف
                    </button>
                    <button wire:click="deleteConfirmed"
                        class="px-8 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        حذف
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- استایل‌های سفارشی -->
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

            input[list]::-webkit-calendar-picker-indicator {
                display: none !important;
            }

            .animate-fadeIn {
                animation: fadeIn 0.3s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </div>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/changers-deal.blade.php ENDPATH**/ ?>