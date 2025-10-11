<div>
    <div class="flex flex-col pr-20 mx-auto">
        <div class="flex flex-col p-4 space-y-3">
            <h1 class="text-[25px] vazir">گزارش حساب و بیلانس</h1>
            <h1 class="text-[#8C8C8C] border-b border-[#D9D9D9] pb-6">لیست تمام مشتریان و خزانه</h1>
            <h1 class="text-[24px] font-medium">گزارش اختصاصـــــی</h1>
        </div>

        
        <div class="w-full max-w-[1465px] bg-[#F5F5F5] rounded-[12px] p-6 mx-auto"
            style="box-shadow: 0px 4px 4px 0px #00000040;">
            <form wire:submit.prevent="loadTransactions" class="space-y-8">

                <div class="flex flex-col md:flex-row gap-8">
                    <!-- ستون سمت راست -->
                    <div class="flex-1 flex flex-col space-y-6">

                        
                        <div class="flex-1">
                            <div class="relative w-full">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">نمبر حساب</label>

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
                        // ✅ فراخوانی متد Livewire برای انتخاب مشتری
                        $wire.selectCustomer(selected.id);
                    } else {
                        // اگر چیزی اشتباه وارد شد، مقدار پاک شود
                        this.selectedId = null;
                        this.searchValue = '';
                        $wire.set('selectedAccount', null);
                    }
                },

                updateDisplay() {
                    const selected = this.customers.find(c => c.id === this.selectedId);
                    this.searchValue = selected ? `${selected.account_number} - ${selected.fullname}` : '';
                }
            }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())" class="relative w-full">
                                    <input list="customersList" x-model="searchValue" @change="handleSelect"
                                        placeholder="جستجو یا انتخاب حساب..."
                                        class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                        autocomplete="off">

                                    <datalist id="customersList">
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                        </option>
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


                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">نوع سند</label>
                            <select wire:model="typeDocument"
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <option value="">همه اسناد</option>
                                <option value="خرید">خرید</option>
                                <option value="فروش">فروش</option>
                                <option value="انتقال">انتقال</option>
                                <option value="دریافت">دریافت</option>
                                <option value="پرداخت">پرداخت</option>
                            </select>
                        </div>

                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">نوع معامله</label>
                            <select wire:model="typeTransaction"
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <option value="">همه معاملات</option>
                                <option value="رسید">رسید</option>
                                <option value="برد">برد</option>
                            </select>
                        </div>

                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">توضیحات</label>
                            <input type="text" wire:model="description"
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400 placeholder:text-[#404040]"
                                placeholder="درج توضیحات">
                        </div>
                    </div>

                    <!-- ستون سمت چپ -->
                    <div class="flex-1 flex flex-col space-y-6">

                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">نوع گزارش</label>
                            <select
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <option value="">همه ترانزکشن‌ها</option>
                                <option value="">رسید</option>
                                <option value="">برد</option>
                            </select>
                        </div>

                        
                        <div class="relative w-full" x-data="{ 
                                    open: false, 
                                    selectedCurrencies: <?php if ((object) ('selectedCurrencies') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedCurrencies'->value()); ?>')<?php echo e('selectedCurrencies'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedCurrencies'); ?>')<?php endif; ?>, 
                                    currencyMap: <?php echo \Illuminate\Support\Js::from(collect($currencies)->mapWithKeys(fn($c) => [$c['code'] => $c['name_fa']])->toArray())->toHtml() ?> 
                                }">
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">
                                انتخاب واحد ارز برای گزارش
                            </label>

                            <!-- Container کلیک‌شدنی و نمایش انتخاب‌ها به جای placeholder -->
                            <div @click="open = !open"
                                class="flex flex-wrap gap-2 items-center min-h-[59px] border border-[#8C8C8C] rounded-[12px] p-2 cursor-pointer">
                                <template x-if="selectedCurrencies.length > 0">
                                    <template x-for="code in selectedCurrencies" :key="code">
                                        <span
                                            class="bg-blue-100 text-blue-800 px-2 py-1 rounded-md flex items-center gap-1">
                                            <span x-text="currencyMap[code] || code"></span>
                                            <button type="button"
                                                @click.stop="selectedCurrencies = selectedCurrencies.filter(c => c !== code)"
                                                class="text-blue-600 hover:text-red-600">×</button>
                                        </span>
                                    </template>
                                </template>
                                <template x-if="selectedCurrencies.length === 0">
                                    <span class="text-gray-400">انتخاب ارز...</span>
                                </template>
                            </div>

                            <!-- Dropdown چک‌باکس‌ها -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute z-50 w-full bg-white border border-gray-300 mt-1 max-h-60 overflow-y-auto rounded-md shadow-lg">
                                <div class="p-2 flex flex-wrap gap-2">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label
                                        class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 cursor-pointer border border-transparent hover:border-blue-200">
                                        <input type="checkbox" value="<?php echo e($currency['code']); ?>"
                                            x-model="selectedCurrencies"
                                            class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                        <span class="text-gray-700 font-medium"><?php echo e($currency['name_fa']); ?></span>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>

                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">زون</label>
                                <select wire:model="zone"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                    <option value="">انتخاب زون</option>
                                    <option value="غرب">غرب (هرات، بادغیس، غور، فراه)</option>
                                    <option value="مرکز">مرکز (کابل، پروان، کاپیسا، وردک، لوگر)</option>
                                    <option value="شمال">شمال (بلخ، جوزجان، سرپل، سمنگان، فاریاب)</option>
                                    <option value="شمال‌شرق">شمال‌شرق (کندز، تخار، بدخشان، بغلان)</option>
                                    <option value="جنوب">جنوب (قندهار، ارزگان، زابل، هلمند)</option>
                                    <option value="جنوب‌شرق">جنوب‌شرق (خوست، پکتیا، پکتیکا)</option>
                                    <option value="شرق">شرق (ننگرهار، لغمان، کنر، نورستان)</option>
                                    <option value="جنوب‌غرب">جنوب‌غرب (نیمروز)</option>
                                </select>
                            </div>

                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">توسط</label>
                                <input type="text" wire:model="by"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400 placeholder:text-[#404040]"
                                    placeholder="جستجو توسط">
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-4" x-data="{
                                initDatepickers() {
                                    const afghanMonths = [
                                        'حمل', 'ثور', 'جوزا', 'سرطان', 
                                        'اسد', 'سنبله', 'میزان', 'عقرب', 
                                        'قوس', 'جدی', 'دلو', 'حوت'
                                    ];

                                    // تقویم تاریخ شروع
                                    $('#startDate').persianDatepicker({
                                        format: 'YYYY/MM/DD',
                                        autoClose: true,
                                        initialValue: <?php echo \Illuminate\Support\Js::from($startDate)->toHtml() ?>,
                                        initialValueType: 'persian',
                                        position: 'auto',
                                        calendar: {
                                            persian: {
                                                locale: 'fa',
                                                showHint: true,
                                                leapYearMode: 'algorithmic',
                                                epochs: [1348, 1348]
                                            }
                                        },
                                        onSelect: (unixTimestamp) => {
                                            const selectedDate = new PersianDate(unixTimestamp);
                                            const year = selectedDate.year();
                                            const month = selectedDate.month();
                                            const day = selectedDate.date();
                                            
                                            const dateString = year + '/' + 
                                                            (month < 10 ? '0' + month : month) + '/' + 
                                                            (day < 10 ? '0' + day : day);
                                            
                                            window.Livewire.find('<?php echo e($_instance->getId()); ?>').setStartDate(dateString);
                                        }
                                    });

                                    // تقویم تاریخ پایان
                                    $('#endDate').persianDatepicker({
                                        format: 'YYYY/MM/DD',
                                        autoClose: true,
                                        initialValue: <?php echo \Illuminate\Support\Js::from($endDate)->toHtml() ?>,
                                        initialValueType: 'persian',
                                        position: 'auto',
                                        calendar: {
                                            persian: {
                                                locale: 'fa',
                                                showHint: true,
                                                leapYearMode: 'algorithmic',
                                                epochs: [1348, 1348]
                                            }
                                        },
                                        onSelect: (unixTimestamp) => {
                                            const selectedDate = new PersianDate(unixTimestamp);
                                            const year = selectedDate.year();
                                            const month = selectedDate.month();
                                            const day = selectedDate.date();
                                            
                                            const dateString = year + '/' + 
                                                            (month < 10 ? '0' + month : month) + '/' + 
                                                            (day < 10 ? '0' + day : day);
                                            
                                            window.Livewire.find('<?php echo e($_instance->getId()); ?>').setEndDate(dateString);
                                        }
                                    });

                                    // جایگزینی ماه‌ها در تقویم
                                    this.replaceCalendarMonths();
                                },

                                replaceCalendarMonths() {
                                    // تابع برای جایگزینی ماه‌ها
                                    const replaceMonths = () => {
                                        // جایگزینی ماه‌ها در هدر تقویم
                                        $('.pdp-monthyear').each(function() {
                                            let text = $(this).text();
                                            text = text
                                                .replace(/فروردین/g, 'حمل')
                                                .replace(/اردیبهشت/g, 'ثور')
                                                .replace(/خرداد/g, 'جوزا')
                                                .replace(/تیر/g, 'سرطان')
                                                .replace(/مرداد/g, 'اسد')
                                                .replace(/شهریور/g, 'سنبله')
                                                .replace(/مهر/g, 'میزان')
                                                .replace(/آبان/g, 'عقرب')
                                                .replace(/آذر/g, 'قوس')
                                                .replace(/دی/g, 'جدی')
                                                .replace(/بهمن/g, 'دلو')
                                                .replace(/اسفند/g, 'حوت');
                                            $(this).text(text);
                                        });

                                        // جایگزینی ماه‌ها در منوی انتخاب ماه
                                        $('.pdp-month-container span, .pdp-month').each(function() {
                                            let text = $(this).text();
                                            text = text
                                                .replace(/فروردین/g, 'حمل')
                                                .replace(/اردیبهشت/g, 'ثور')
                                                .replace(/خرداد/g, 'جوزا')
                                                .replace(/تیر/g, 'سرطان')
                                                .replace(/مرداد/g, 'اسد')
                                                .replace(/شهریور/g, 'سنبله')
                                                .replace(/مهر/g, 'میزان')
                                                .replace(/آبان/g, 'عقرب')
                                                .replace(/آذر/g, 'قوس')
                                                .replace(/دی/g, 'جدی')
                                                .replace(/بهمن/g, 'دلو')
                                                .replace(/اسفند/g, 'حوت');
                                            $(this).text(text);
                                        });
                                    };

                                    // اجرای اولیه
                                    replaceMonths();

                                    // اجرای هر 500 میلی‌ثانیه تا زمانی که تقویم لود شود
                                    const interval = setInterval(() => {
                                        if ($('.pdp-monthyear').length > 0) {
                                            replaceMonths();
                                        }
                                    }, 500);

                                    // توقف بعد از 5 ثانیه
                                    setTimeout(() => {
                                        clearInterval(interval);
                                    }, 5000);

                                    // همچنین وقتی روی input کلیک می‌شود
                                    $(document).on('click', '#startDate, #endDate', () => {
                                        setTimeout(replaceMonths, 300);
                                    });

                                    // وقتی تقویم باز می‌شود
                                    $(document).on('click', '.pdp-header', () => {
                                        setTimeout(replaceMonths, 300);
                                    });
                                }
                            }" x-init="initDatepickers()">

                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">تاریخ شروع</label>
                                <div class="relative">
                                    <input type="text" id="startDate" wire:model="startDateDisplay"
                                        class="w-full pr-12 h-[59px] rounded-[12px] bg-white border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400 placeholder:text-[#404040] cursor-pointer shadow-sm"
                                        placeholder="1404/حمل/01" readonly>
                                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        📅
                                    </div>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['startDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">تاریخ ختم</label>
                                <div class="relative">
                                    <input type="text" id="endDate" wire:model="endDateDisplay"
                                        class="w-full pr-12 h-[59px] rounded-[12px] bg-white border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400 placeholder:text-[#404040] cursor-pointer shadow-sm"
                                        placeholder="1404/جوزا/01" readonly>
                                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        📅
                                    </div>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['endDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        <style>
                            /* استایل برای ماه‌های افغانی */
                            .pdp-monthyear,
                            .pdp-month-container span,
                            .pdp-month {
                                font-family: system-ui, -apple-system, sans-serif !important;
                                font-weight: 500 !important;
                            }

                            .persian-date-picker-table td {
                                font-family: system-ui, -apple-system, sans-serif !important;
                            }
                        </style>

                    </div>
                </div>

                <!-- در بخش دکمه‌ها -->
                <div class="flex justify-center gap-4 pt-4">
                    <button type="submit"
                        class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-[16px] font-medium rounded-[12px] w-full px-8 py-4 transition">
                        بروزرسانی گزارش
                    </button>

                    <button type="button" wire:click="print" wire:loading.attr="disabled"
                        class="bg-[#B10909] hover:bg-[#8B0000] text-white text-[16px] font-medium rounded-[12px] w-full py-4 transition flex items-center justify-center gap-2">
                        <span wire:loading.remove>چاپ گزارش</span>
                        <span wire:loading>
                            در حال تولید...
                        </span>
                    </button>

                    
                </div>

            </form>
        </div>

        
        <div class="w-full max-w-[1465px] bg-[#F5F5F5] rounded-[12px] mt-10 p-6 mx-auto"
            style="box-shadow: 0px 4px 4px 0px #00000040;">

            <div class="flex justify-between items-center mb-4">
                <div class="relative w-[302px]">
                    <input type="text" id="searchTable"
                        class="border border-[#8C8C8C] bg-transparent rounded-[12px] h-[51px] w-[302px] pl-10 pr-4"
                        placeholder="جستجو در جدول...">
                    <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt="search"
                        class="absolute left-3 top-3 w-5 h-5">
                </div>

                <!--[if BLOCK]><![endif]--><?php if($selectedCustomer): ?>
                <div class="text-right">
                    <h3 class="text-lg font-bold"><?php echo e($selectedCustomerName); ?></h3>
                    <p class="text-sm text-gray-600">تعداد تراکنش‌ها: <?php echo e(count($transactions)); ?></p>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <table class="w-full text-sm md:text-base text-left mt-6 rtl:text-right text-gray-500 dark:text-gray-400">
                <thead
                    class="bg-[#2B65E5] w-full text-white text-[14px] md:text-[16px] h-[50px] md:h-[67px] sticky top-0"
                    style="box-shadow: 0px 4px 4px 0px #00000040;">
                    <!-- سطر اول -->
                    <tr class="w-full">
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-12 md:w-16" rowspan="2">#</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-32 md:w-48" rowspan="2">تاریخ</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-24 md:w-32" rowspan="2">نمبر سند</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-32 md:w-40" rowspan="2">توضیحات</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-24 md:w-32" rowspan="2">توسط</th>

                        <!-- نمایش داینامیک ارزها بر اساس مشتری -->
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $active_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $currencyName = is_array($currency) ? $currency['name_fa'] : $currency;
                        $colspan = 2;
                        ?>
                        <th class="px-4 md:px-6 py-3 md:py-4 font-bold text-center" colspan="<?php echo e($colspan); ?>">
                            <?php echo e($currencyName); ?>

                        </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                        <th class="px-2 md:px-4 py-3 md:py-4 font-bold w-36 md:w-48 text-center" rowspan="2">تسویه</th>
                    </tr>
                    <!-- سطر دوم -->
                    <tr>
                        <!-- نمایش ستون‌های رسید و برد برای هر ارز -->
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $active_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="px-2 md:px-3 py-2 font-semibold text-center min-w-[70px] md:min-w-[80px]">رسید</th>
                        <th class="px-2 md:px-3 py-2 font-semibold text-center min-w-[70px] md:min-w-[80px]">برد</th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tr>
                </thead>

                <tbody class="text-[14px] md:text-[15px] text-gray-800">
                    <!--[if BLOCK]><![endif]--><?php if(count($transactions) > 0): ?>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-2 md:px-4 py-3 text-center"><?php echo e($index + 1); ?></td>
                        <td class="px-2 md:px-4 py-3">
                            <div class="flex flex-col">
                                <span><?php echo e($transaction->date); ?></span>
                            </div>
                        </td>
                        <td class="px-2 md:px-4 py-3"><?php echo e($transaction->document_number ?? 'SN-' .
                            str_pad($transaction->id, 3, '0', STR_PAD_LEFT)); ?></td>
                        <td class="px-2 md:px-4 py-3"><?php echo e($transaction->description); ?></td>
                        <td class="px-2 md:px-4 py-3"><?php echo e($transaction->by); ?></td>

                        <!-- نمایش داینامیک مقادیر برای هر ارز -->
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $active_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="px-2 md:px-3 py-3 text-center">
                            <?php echo e($transaction->currency == $code && $transaction->type == 'رسید' ?
                            number_format($transaction->amount) : '-'); ?>

                        </td>
                        <td class="px-2 md:px-3 py-3 text-center">
                            <?php echo e($transaction->currency == $code && $transaction->type == 'برد' ?
                            number_format($transaction->amount) : '-'); ?>

                        </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                        <td class="px-2 md:px-4 py-3 text-center">
                            <span
                                class="px-2 py-1 rounded-full text-xs <?php echo e($transaction->status == 'تأیید شده' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                <?php echo e($transaction->status ?? 'در انتظار'); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    <?php else: ?>
                    <tr>
                        <td colspan="<?php echo e(5 + (count($active_currencies) * 2) + 1); ?>"
                            class="px-4 py-8 text-center text-gray-500">
                            <!--[if BLOCK]><![endif]--><?php if($selectedCustomer): ?>
                            هیچ تراکنشی با فیلترهای انتخاب شده یافت نشد
                            <?php else: ?>
                            لطفاً ابتدا یک مشتری را انتخاب کنید
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>
                    </tr>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>

        </div>

        
        
        <div class="w-full max-w-[1465px] bg-[#F5F5F5] rounded-[12px] mt-10 p-6 mx-auto">

            <div class="flex justify-between items-center text-center mx-auto mb-6">
                <h1 class="text-xl font-bold">مجموعه کل</h1>
                <button
                    class="w-[31px] h-[29.232500076293945px] rounded-[8px] bg-transparent border border-[#000000] pr-1 ">
                    <img src="<?php echo e(asset('assets/sarafi/all_icon/printer.svg')); ?>" alt=""
                        class="w-[21.0575008392334px] h-[19.232500076293945px]">
                </button>
            </div>

            <table class="w-full text-sm md:text-base text-left mt-6 rtl:text-right text-gray-500 dark:text-gray-400">
                <thead
                    class="bg-[#2B65E5] w-full text-white text-[14px] md:text-[16px] h-[50px] md:h-[67px] sticky top-0"
                    style="box-shadow: 0px 4px 4px 0px #00000040;">
                    <!-- سطر اول -->
                    <tr>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-12 md:w-16" rowspan="2">#</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-32 md:w-48" rowspan="2">واحد پول</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-24 md:w-32" rowspan="2">موجودی قبلی</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-32 md:w-40" rowspan="2">رسید</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-24 md:w-32" rowspan="2">برد</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-24 md:w-32" rowspan="2">بیلانس</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-24 md:w-32" rowspan="2">موجودی فعلی</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-24 md:w-32" rowspan="2">وضعیت</th>
                    </tr>
                </thead>

                <tbody class="text-[18px] md:text-[18px] text-gray-800">
                    <!--[if BLOCK]><![endif]--><?php if(count($balances) > 0): ?>
                    <?php $counter = 1; ?>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $balances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $balance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            <?php echo e($counter++); ?>

                        </td>

                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            <?php echo e($balance['name_fa']); ?>

                        </td>

                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            <?php echo e(number_format($balance['previous_balance'])); ?>

                        </td>

                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            <?php echo e(number_format($balance['received'])); ?>

                        </td>

                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            <?php echo e(number_format($balance['spent'])); ?>

                        </td>

                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            <?php echo e(number_format($balance['balance'])); ?>

                        </td>

                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            <?php echo e(number_format($balance['current_balance'])); ?>

                        </td>

                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            <span
                                class="px-2 py-1 rounded-full text-xs <?php echo e($balance['status'] == 'طلبکار' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e($balance['status']); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            هیچ موجودی فعالی وجود ندارد
                        </td>
                    </tr>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>

        </div>
    </div>

    

</div>

<script>
    // جستجوی ساده در جدول
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchTable');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchText)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>

<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/transactions-reports.blade.php ENDPATH**/ ?>