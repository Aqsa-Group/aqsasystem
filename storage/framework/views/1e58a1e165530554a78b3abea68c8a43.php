<?php
// تابع تبدیل کد ارز به نام فارسی
function getPersianCurrencyName($currencyCode) {
$currencyMap = [
'afn' => 'افغانی',
'usd' => 'دالر',
'irr' => 'تومان',
'eur' => 'یورو',
'pkr' => 'کلدار',
'aed' => 'درهم',
'try' => 'لیره',
'cny' => 'یوان',
'gbp' => 'پوند',
'jpy' => 'ین',
'sar' => 'ریال سعودی',
'inr' => 'روپیه',
];

$currencyCode = strtolower($currencyCode ?? 'usd');
return $currencyMap[$currencyCode] ?? $currencyCode;
}

// دریافت نام ارز مبدا به فارسی
$latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();
$sourceCurrency = getPersianCurrencyName($latestProfitRate->source_currency ?? 'usd');
?>

<div class="px-5">
    <div class="w-[1200px]">
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-3 mb-5">
            <button wire:click="selectCategory('customers')"
                class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px] hover:bg-blue-700 transition-colors">
                گزارشات مالی مشتریان
            </button>
            <button wire:click="selectCategory('accounts')"
                class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px] hover:bg-blue-700 transition-colors">
                گزارش حسابات و صندوق
            </button>
            <button wire:click="selectCategory('transactions')"
                class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px] hover:bg-blue-700 transition-colors">
                گزارشات تراکنش های معاملات
            </button>
            <button wire:click="selectCategory('management')"
                class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px] hover:bg-blue-700 transition-colors">
                گزارشات مدیریتی و تحلیلی
            </button>
        </div>
    </div>

    <div class="flex-1 flex flex-col dark:bg-black dark:border-white dark:border dark:text-white  bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

        <!--[if BLOCK]><![endif]--><?php if($selectedCategory): ?>
        <div class="mb-5 relative w-full">
            <select wire:model.live="selectedSubCategory"
                class="border bg-transparent dark:bg-black dark:text-white border-[#8C8C8C] rounded-[12px] px-4 py-2 pt-[13px] pr-[9px] pl-[9px] pb-[13px] w-full appearance-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">انتخاب زیرشاخه</option>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $subCategories[$selectedCategory]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($sub); ?>"><?php echo e($sub); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </select>

            <!-- آیکون سفارشی -->
            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓" class="w-4 h-4">
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- نمایش محتوای زیرشاخه -->
        <!--[if BLOCK]><![endif]--><?php if($selectedSubCategory): ?>
        <!--[if BLOCK]><![endif]--><?php switch($selectedSubCategory):
        case ('گزارش بیلانس مشتریان'): ?>
        <div class="overflow-x-auto w-full mt-4 mb-8">
            <div class="flex flex-col max-h-[600px] overflow-y-auto">
                <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500">
                    <thead class="bg-[#2B65E5]  text-white text-[16px] vazir h-16 sticky top-0">
                        <tr>
                            <th class="px-4 py-4 font-bold w-16">
                                <span class="border border-white px-2 py-1 rounded-lg">#</span>
                            </th>
                            <th class="px-4 py-4 font-bold">نمبرحساب</th>
                            <th class="px-4 py-4 font-bold">نام حساب</th>
                            <th class="px-4 py-4 font-bold">آخرین تاریخ</th>
                            <th class="px-4 py-4 font-bold">دالر</th>
                            <th class="px-4 py-4 font-bold">افغانی</th>
                            <th class="px-4 py-4 font-bold">تومان</th>
                            <th class="px-4 py-4 font-bold">کلدار</th>
                            <th class="px-4 py-4 font-bold">یورو</th>
                            <th class="px-4 py-4 font-bold">درهم</th>
                            <th class="px-4 py-4 font-bold">لیره</th>
                            <th class="px-4 py-4 font-bold">یوان</th>
                            <th class="px-4 py-4 font-bold">بیلانس به <?php echo e($sourceCurrency); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b dark:text-white hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <td class="px-4 py-4">
                                <span
                                    class="px-2 py-1 rounded-lg bg-gray-100 dark:bg-black dark:text-white dark:border-white dark:border"><?php echo e($index + 1); ?></span>
                            </td>
                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                <?php echo e($report['account_number']); ?>

                            </td>
                            <td class="px-4 py-4"><?php echo e($report['fullname']); ?></td>
                            <td class="px-3 py-4">
                                <?php echo e($report['last_date'] ? \Carbon\Carbon::parse($report['last_date'])->format('Y/m/d') :
                                '-'); ?>

                            </td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['usd'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['afn'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['irr'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['pkr'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['eur'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['aed'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['try'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['cny'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 font-medium text-left"><?php echo e(number_format($report['total_balance'], 2)); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="14" class="px-4 py-8 text-center text-gray-500">
                                هیچ داده‌ای یافت نشد
                            </td>
                        </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        </div>
        <?php break; ?>

        <?php case ('گزارش خلاصه بیلانس مشتریان'): ?>
        <div
            class="w-full dark:bg-black dark:text-white dark:border dark:border-white rounded-[16px] bg-[#2563EB] p-4 mb-6">
            <div class="flex-1">
                <div class="relative w-full" x-data="{
                    searchValue: '',
                    searchQuery: '',
                    selectedIds: <?php if ((object) ('selectedAccounts') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccounts'->value()); ?>')<?php echo e('selectedAccounts'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccounts'); ?>')<?php endif; ?>,
                    customers: <?php echo \Illuminate\Support\Js::from($customers->toArray())->toHtml() ?>,
                    isOpen: false,
                    selectedCustomers: [],
                    get filteredCustomers() {
                        if (this.searchQuery === '') return this.customers;
                        const query = this.searchQuery.toLowerCase();
                        return this.customers.filter(customer =>
                            customer.fullname.toLowerCase().includes(query) ||
                            customer.account_number.toLowerCase().includes(query)
                        );
                    },
                    init() {
                                        this.selectedCustomers = this.customers.filter(customer => 
                                            this.selectedIds.includes(customer.id)
                                        );
                                        this.updateDisplay();
                                    },
                                    toggleCustomer(customer) {
                                        const index = this.selectedCustomers.findIndex(c => c.id === customer.id);
                                        if (index > -1) {
                                            this.selectedCustomers.splice(index, 1);
                                        } else {
                                            this.selectedCustomers.push(customer);
                                        }

                                        this.selectedIds = this.selectedCustomers.map(c => c.id);
                                        this.updateDisplay();
                                        $wire.set('selectedAccounts', this.selectedIds);
                                    },
                                    isSelected(customerId) {
                                        return this.selectedCustomers.some(c => c.id === customerId);
                                    },
                                    updateDisplay() {
                                        this.searchValue = this.selectedCustomers.length
                                            ? this.selectedCustomers.map(c => `${c.account_number} - ${c.fullname}`).join(', ')
                                            : '';
                                    },
                                    clearAll() {
                                        this.selectedCustomers = [];
                                        this.selectedIds = [];
                                        this.searchQuery = '';
                                        this.updateDisplay();
                                        $wire.set('selectedAccounts', []);
                                    }
                                }" x-init="init()" @click.outside="isOpen = false">

                    <!-- Input Field -->
                    <div @click="isOpen = true" class="relative">
                        <input type="text" x-model="searchValue" placeholder="انتخاب مشتری"
                            class="w-full h-[30px] p-3 pr-10 rounded-[12px]  bg-transparent  cursor-pointer text-white placeholder-white"
                            readonly>

                        <!-- Dropdown Arrow -->
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': isOpen}"
                                class="transition-transform">
                                <path
                                    d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                    stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>

                        <!-- Clear Button -->
                        <template x-if="selectedCustomers.length > 0">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer"
                                @click.stop="clearAll()">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 6L6 18M6 6l12 12" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </template>
                    </div>

                    <!-- Dropdown Menu -->
                    <div x-show="isOpen" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="absolute z-50 w-full mt-1 bg-white  rounded-md shadow-lg max-h-60 overflow-auto"
                        @click.stop>

                        <!-- Search Box inside Dropdown -->
                        <div class="sticky top-0 bg-white p-2 border-b">
                            <input type="text" x-model="searchQuery" placeholder="جستجوی مشتری..."
                                class="w-full p-2   rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Customers List -->
                        <template x-for="customer in filteredCustomers" :key="customer.id">
                            <label
                                class="flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer border-b transition-colors">
                                <!-- Checkbox سمت چپ -->
                                <input type="checkbox" :checked="isSelected(customer.id)"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                    @click.stop="toggleCustomer(customer)">

                                <!-- Customer Info -->
                                <div class="flex-1 text-right mr-3">
                                    <div class="font-medium text-gray-900" x-text="customer.fullname"></div>
                                    <div class="text-sm text-gray-500"
                                        x-text="'شماره حساب: ' + customer.account_number"></div>
                                </div>
                            </label>
                        </template>

                        <!-- No Results -->
                        <div x-show="filteredCustomers.length === 0" class="px-3 py-2 text-gray-500 text-center">
                            مشتری یافت نشد
                        </div>
                    </div>

                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedAccounts'];
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
        </div>

        <h1 class="mt-5 mr-4 text-xl font-bold text-gray-800">گزارش خلاصه بیلانس مشتریان انتخاب شده</h1>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 p-4 mb-8">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $totalBalances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyCode => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div
                class="flex flex-col bg-white justify-center items-center gap-3 rounded-xl py-6 px-4 shadow-lg border border-gray-200 hover:shadow-xl transition-shadow">
                <p class="text-gray-500 text-sm font-medium"><?php echo e($data['currency_name']); ?></p>
                <p class="text-2xl font-bold text-gray-800 times" dir="ltr">
                    <?php echo e(number_format($data['total'])); ?>

                </p>
                <p class="text-gray-400 text-xs font-medium uppercase">
                    <!--[if BLOCK]><![endif]--><?php switch($currencyCode):
                    case ('usd'): ?> USD <?php break; ?>
                    <?php case ('afn'): ?> AFN <?php break; ?>
                    <?php case ('irr'): ?> IRR <?php break; ?>
                    <?php case ('eur'): ?> EUR <?php break; ?>
                    <?php case ('pkr'): ?> PKR <?php break; ?>
                    <?php case ('aed'): ?> AED <?php break; ?>
                    <?php case ('try'): ?> TRY <?php break; ?>
                    <?php case ('cny'): ?> CNY <?php break; ?>
                    <?php default: ?> <?php echo e($currencyCode); ?>

                    <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                </p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- نمودار میله‌ای -->
        <div class=" p-6  mb-8">
            <svg width="100%" height="350" viewBox="0 0 1000 350" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="barGradient" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#153885" />
                        <stop offset="100%" stop-color="#2563EB" />
                    </linearGradient>
                </defs>

                <!-- محور عمودی سمت چپ برای جدا کردن مقادیر -->
                <line x1="129" y1="30" x2="129" y2="280" stroke="#94a3b8" stroke-width="2" />

                <!-- محور افقی -->
                <line x1="100" y1="280" x2="950" y2="280" stroke="#94a3b8" stroke-width="2" />

                <!-- مقادیر عمودی سمت چپ (چپ‌چین شده) -->
                <text dir="ltr" x="90" y="285" font-family="Arial" font-size="12" text-anchor="end"
                    fill="#64748b">0</text>
                <text dir="ltr" x="90" y="235" font-family="Arial" font-size="12" text-anchor="end" fill="#64748b"><?php echo e(number_format($maxValue * 0.2, 0)); ?></text>
                <text dir="ltr" x="90" y="185" font-family="Arial" font-size="12" text-anchor="end" fill="#64748b"><?php echo e(number_format($maxValue * 0.4, 0)); ?></text>
                <text dir="ltr" x="90" y="135" font-family="Arial" font-size="12" text-anchor="end" fill="#64748b"><?php echo e(number_format($maxValue * 0.6, 0)); ?></text>
                <text dir="ltr" x="90" y="85" font-family="Arial" font-size="12" text-anchor="end" fill="#64748b"><?php echo e(number_format($maxValue * 0.8, 0)); ?></text>
                <text dir="ltr" x="90" y="35" font-family="Arial" font-size="12" text-anchor="end" fill="#64748b"><?php echo e(number_format($maxValue, 0)); ?></text>

                <!-- خطوط راهنمای افقی -->
                <line x1="100" y1="280" x2="950" y2="280" stroke="#e2e8f0" stroke-width="1" />
                <line x1="100" y1="230" x2="950" y2="230" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4" />
                <line x1="100" y1="180" x2="950" y2="180" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4" />
                <line x1="100" y1="130" x2="950" y2="130" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4" />
                <line x1="100" y1="80" x2="950" y2="80" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4" />
                <line x1="100" y1="30" x2="950" y2="30" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4" />

                <!-- میله‌های نمودار -->
                <?php
                $chartHeight = 250; // 280 - 30
                $barWidth = 50;
                $spacing = 30;
                $startX = 140; // افزایش یافته تا از خط جداکننده فاصله بگیرد
                ?>

                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $chartData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $barHeight = $maxValue > 0 ? ($item['value'] / $maxValue) * $chartHeight : 0;
                $x = $startX + ($index * ($barWidth + $spacing));
                $y = 280 - $barHeight;

                // فرمت مقدار برای نمایش
                $displayValue = $item['value'] >= 1000000
                ? number_format($item['value'] / 1000000, 1) . 'M'
                : ($item['value'] >= 1000
                ? number_format($item['value'] / 1000, 1) . 'K'
                : number_format($item['value'], 0));
                ?>

                <!-- میله -->
                <rect x="<?php echo e($x); ?>" y="<?php echo e($y); ?>" width="<?php echo e($barWidth); ?>" height="<?php echo e($barHeight); ?>"
                    fill="url(#barGradient)" rx="2" />

                <!-- مقدار بالای میله -->
                <text x="<?php echo e($x + $barWidth / 2); ?>" y="<?php echo e($y - 10); ?>" font-family="Arial" font-size="12"
                    text-anchor="middle" fill="#374151">
                    <?php echo e($displayValue); ?>

                </text>

                <!-- نام ارز زیر میله -->
                <text x="<?php echo e($x + $barWidth / 2); ?>" y="300" font-family="Arial" font-size="16" text-anchor="middle"
                    fill="#374151">
                    <?php echo e($item['currency']); ?>

                </text>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </svg>
        </div>
        <?php break; ?>
        <?php case ('طلب مشتری ها'): ?>
        <div class="overflow-x-auto w-full mt-4 mb-8">
            <div class="flex flex-col max-h-[600px] overflow-y-auto">

                <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500">

                    
                    <thead class="bg-[#2B65E5] text-white text-[16px] vazir h-16 sticky top-0">
                        <tr>
                            <th class="px-4 py-4 font-bold w-16">
                                <span class="border border-white px-2 py-1 rounded-lg">#</span>
                            </th>
                            <th class="px-4 py-4 font-bold">نمبر حساب</th>
                            <th class="px-4 py-4 font-bold">نام حساب</th>
                            <th class="px-4 py-4 font-bold">آخرین تاریخ</th>

                            
                            <th class="px-4 py-4 font-bold">دالر</th>
                            <th class="px-4 py-4 font-bold">افغانی</th>
                            <th class="px-4 py-4 font-bold">تومان</th>
                            <th class="px-4 py-4 font-bold">کلدار</th>
                            <th class="px-4 py-4 font-bold">یورو</th>
                            <th class="px-4 py-4 font-bold">درهم</th>
                            <th class="px-4 py-4 font-bold">لیره</th>
                            <th class="px-4 py-4 font-bold">یوان</th>

                            
                            <th class="px-4 py-4 font-bold">بیلانس به <?php echo e($sourceCurrency); ?></th>
                        </tr>
                    </thead>

                    
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $demands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $demand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b hover:bg-gray-50 transition-colors">

                            
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 rounded-lg bg-gray-100"><?php echo e($index + 1); ?></span>
                            </td>

                            
                            <td class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <?php echo e($demand['account_number']); ?>

                            </td>

                            
                            <td class="px-4 py-4">
                                <?php echo e($demand['fullname']); ?>

                            </td>

                            
                            <td class="px-3 py-4">
                                <?php echo e($demand['last_date'] ? \Carbon\Carbon::parse($demand['last_date'])->format('Y/m/d') :
                                '-'); ?>

                            </td>

                            
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($demand['balances']['usd'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($demand['balances']['afn'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($demand['balances']['irr'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($demand['balances']['pkr'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($demand['balances']['eur'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($demand['balances']['aed'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($demand['balances']['try'] ?? 0,
                                2)); ?></td>
                            <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($demand['balances']['cny'] ?? 0,
                                2)); ?></td>

                            
                            <td class="px-4 py-4 font-medium text-left">
                                <?php echo e(number_format($demand['total_balance'], 2)); ?>

                            </td>
                        </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="14" class="px-4 py-8 text-center text-gray-500">
                                هیچ داده‌ای یافت نشد
                            </td>
                        </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>

                </table>

            </div>
        </div>
        <?php break; ?>

        <?php default: ?>
        <div class="border p-5 rounded bg-gray-50">
            <h3 class="font-bold text-lg mb-3"><?php echo e($selectedSubCategory); ?></h3>
            <p>این گزارش در حال توسعه می‌باشد</p>
        </div>
        <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- بخش گزارش بیلانس مشتریان (کارت‌ها و نمودار دایره‌ای) -->
    <!--[if BLOCK]><![endif]--><?php if($selectedSubCategory == 'گزارش بیلانس مشتریان'): ?>
    <div class="flex-1 flex flex-col bg-[#F5F5F5] h-fit p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <div class="flex w-full flex-col lg:flex-row">
            <div class="lg:w-1/2 mb-6 lg:mb-0">
                <div class="flex-1">
                    <div class="relative w-full lg:w-[589px]">
                        <div x-data="{
                                searchValue: '',
                                selectedId: <?php if ((object) ('selectedAccount') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'->value()); ?>')<?php echo e('selectedAccount'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'); ?>')<?php endif; ?>,
                                customers: <?php echo \Illuminate\Support\Js::from($customers->toArray())->toHtml() ?>,
                                handleSelect(event) {
                                    const selected = this.customers.find(
                                        c => event.target.value === `${c.account_number} - ${c.fullname}`
                                    );
                                    if (selected) {
                                        this.selectedId = selected.id;
                                        this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                        $wire.call('selectCustomer', selected.id);
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
                </div>

                <!-- نمایش موجودی‌ها -->
                <!--[if BLOCK]><![endif]--><?php if($selectedCustomerId): ?>
                <?php
                $hasNonZeroBalance = false;
                foreach($selectedCustomerBalance as $balance) {
                if (abs($balance['balance']) > 0.001) {
                $hasNonZeroBalance = true;
                break;
                }
                }
                ?>

                <!--[if BLOCK]><![endif]--><?php if($hasNonZeroBalance): ?>
                <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6 mt-6 w-full lg:w-[589px]">
                    <div class="space-y-4">
                        <div
                            class="w-full h-[79px] flex flex-col md:flex-row items-center justify-between p-6 bg-[#2563EB] text-white text-[16px] rounded-[12px]">
                            <p class="vazir font-bold">ارزش کل موجودی</p>
                            <p class="vazir font-bold" dir="ltr">
                                <?php
                                $totalUSD = 0;
                                foreach($selectedCustomerBalance as $balance) {
                                if (abs($balance['balance']) > 0.001) {
                                $totalUSD += $balance['balance_usd'];
                                }
                                }
                                ?>
                                <?php echo e(number_format($totalUSD, 2)); ?>

                                <span>دالر</span>
                            </p>
                        </div>

                        <?php $__currentLoopData = $selectedCustomerBalance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyCode => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <!--[if BLOCK]><![endif]--><?php if(abs($data['balance']) > 0.001): ?>
                        <div
                            class="w-full h-[79px] flex flex-col md:flex-row items-center justify-between p-6 bg-transparent border border-[#2563EB] text-black text-[16px] rounded-[12px] hover:bg-blue-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full"
                                    style="background-color: <?php echo e($this->getCurrencyColor($currencyCode)); ?>">
                                </div>
                                <span class="vazir font-bold"><?php echo e($data['currency_name']); ?></span>
                            </div>
                            <div class="text-left" dir="ltr">
                                <p class="vazir font-bold"><?php echo e(number_format($data['balance'], 2)); ?></p>
                            </div>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <?php else: ?>
                <div class="text-center py-8">
                    <p class="text-gray-500 vazir">این مشتری موجودی ندارد</p>
                    <!-- اطلاعات دیباگ -->
                    <div class="mt-4 p-3 bg-gray-100 rounded text-xs text-gray-600">
                        <p>Customer ID: <?php echo e($selectedCustomerId); ?></p>
                        <p>Balances Count: <?php echo e(count($selectedCustomerBalance)); ?></p>
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php else: ?>
                <div class="text-center py-8">
                    <p class="text-gray-500 vazir">لطفاً یک مشتری انتخاب کنید</p>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- بخش نمودار SVG -->
            <!--[if BLOCK]><![endif]--><?php if($selectedCustomerId && count($currencyPercentages) > 0): ?>
            <div class="lg:w-1/2 mt-6 lg:mt-0 flex justify-center" dir="ltr">
                <?php
                $chartData = [
                'series' => [],
                'labels' => [],
                'colors' => [],
                'total' => 0
                ];

                foreach($currencyPercentages as $currency) {
                $chartData['series'][] = $currency['percentage'];
                $chartData['labels'][] = $currency['currency_name'];
                $chartData['colors'][] = $currency['color'];
                $chartData['total'] += $currency['percentage'];
                }

                $radius = 80;
                $circumference = 2 * 3.1416 * $radius;
                $currentOffset = 0;
                ?>

                <div class="p-6 relative">
                    <div class="relative w-full max-w-[400px] h-[350px] mx-auto">
                        <svg width="100%" height="100%" viewBox="0 0 40 40">
                            <!-- تعریف گرادینت‌ها -->
                            <defs>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $chartData['colors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $lighterColor = $this->lightenColor($color, 30);
                                $darkerColor = $this->darkenColor($color, 20);
                                ?>
                                <linearGradient id="gradient-<?php echo e($index); ?>" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="<?php echo e($lighterColor); ?>" />
                                    <stop offset="100%" stop-color="<?php echo e($darkerColor); ?>" />
                                </linearGradient>

                                <radialGradient id="radial-gradient-<?php echo e($index); ?>" cx="50%" cy="50%" r="50%" fx="50%"
                                    fy="50%">
                                    <stop offset="0%" stop-color="<?php echo e($lighterColor); ?>" />
                                    <stop offset="70%" stop-color="<?php echo e($color); ?>" />
                                    <stop offset="100%" stop-color="<?php echo e($darkerColor); ?>" />
                                </radialGradient>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </defs>

                            <?php
                            $total = array_sum($chartData['series']);
                            $startAngle = 0;
                            $radius = 20;
                            ?>

                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $chartData['series']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $percentage = ($value / $total) * 100;
                            $angle = ($value / $total) * 360;

                            $x1 = 20 + $radius * cos(deg2rad($startAngle));
                            $y1 = 20 + $radius * sin(deg2rad($startAngle));

                            $endAngle = $startAngle + $angle;
                            $x2 = 20 + $radius * cos(deg2rad($endAngle));
                            $y2 = 20 + $radius * sin(deg2rad($endAngle));

                            $largeArc = ($angle > 180) ? 1 : 0;
                            $path = "M20,20 L$x1,$y1 A$radius,$radius 0 $largeArc,1 $x2,$y2 Z";

                            $midAngle = $startAngle + $angle / 2;
                            $textX = 20 + ($radius * 0.55) * cos(deg2rad($midAngle));
                            $textY = 20 + ($radius * 0.55) * sin(deg2rad($midAngle));
                            ?>

                            <path d="<?php echo e($path); ?>" fill="url(#radial-gradient-<?php echo e($index); ?>)" stroke="white"
                                stroke-width="0.3"></path>

                            <text dir="ltr" x="<?php echo e($textX); ?>" y="<?php echo e($textY); ?>" font-size="2.7" fill="white"
                                text-anchor="middle" alignment-baseline="middle"
                                style="font-weight: bold; text-shadow: 0px 0px 3px rgba(0,0,0,0.5);">
                                <?php echo e(round($percentage, 1)); ?>%
                            </text>

                            <?php
                            $startAngle += $angle;
                            ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </svg>
                    </div>

                    <!-- لیبل‌ها کنار چارت -->
                    <div class="absolute right-9 flex mt-4  gap-4" dir="ltr">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $chartData['labels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-end gap-2">
                            <div class="w-4 h-4 rounded-full shadow-sm"
                                style="background: linear-gradient(135deg, <?php echo e($this->lightenColor($chartData['colors'][$index], 30)); ?>, <?php echo e($this->darkenColor($chartData['colors'][$index], 20)); ?>);">
                            </div>
                            <span class="text-sm vazir text-gray-700" dir="ltr">
                                <?php echo e($label); ?> (<?php echo e(round($chartData['series'][$index], 1)); ?>%)
                            </span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- بخش کارت‌های مشتریان برای گزارش خلاصه -->
    <!--[if BLOCK]><![endif]--><?php if($selectedSubCategory == 'گزارش خلاصه بیلانس مشتریان' && !empty($selectedCustomersData)): ?>
    <div class="mt-8 bg-[#F5F5F5] p-5 rounded-[16px] mb-5"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <h2 class="text-xl font-bold text-gray-800 mb-6">کارت‌های موجودی مشتریان انتخاب شده</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $selectedCustomersData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            // محاسبه کل موجودی و فیلتر ارزهای دارای موجودی
            $customerTotalUSD = 0;
            $customerCurrenciesWithBalance = [];

            foreach($customer['balances'] as $currencyCode => $balanceData) {
            if (abs($balanceData['balance']) > 0.001) {
            $balanceUSD = $this->convertToUSD($balanceData['balance'], $currencyCode);
            $customerTotalUSD += $balanceUSD;
            $customerCurrenciesWithBalance[$currencyCode] = [
            'balance' => $balanceData['balance'],
            'currency_name' => $balanceData['currency_name'],
            'color' => $this->getCurrencyColor($currencyCode)
            ];
            }
            }
            ?>

            <!-- کارت مشتری -->
            <div class="w-full">
                <div class="space-y-4">
                    <!-- هدر کارت -->
                    <div
                        class="w-full h-[60px] flex flex-col md:flex-row items-center justify-between p-4 bg-[#2563EB] text-white text-[14px] rounded-[12px]">
                        <p class="vazir font-bold truncate" title="<?php echo e($customer['name']); ?>"><?php echo e($customer['name']); ?></p>
                        <p class="vazir font-bold text-sm whitespace-nowrap" dir="ltr">
                            <?php echo e(number_format($customerTotalUSD, 2)); ?> <span class="text-xs">دالر</span>
                        </p>
                    </div>

                    <!-- موجودی‌های ارزی -->
                    <!--[if BLOCK]><![endif]--><?php if(count($customerCurrenciesWithBalance) > 0): ?>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $customerCurrenciesWithBalance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyCode => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div
                        class="w-full h-[50px] flex flex-col md:flex-row items-center justify-between p-4 bg-transparent border border-[#2563EB] text-black text-[14px] rounded-[12px] hover:bg-blue-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full" style="background-color: <?php echo e($data['color']); ?>"></div>
                            <span class="vazir font-bold text-sm"><?php echo e($data['currency_name']); ?></span>
                        </div>
                        <div class="text-left">
                            <p class="vazir font-bold text-sm" dir="ltr"><?php echo e(number_format($data['balance'], 2)); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    <?php else: ?>
                    <div
                        class="w-full h-[50px] flex items-center justify-center p-4 bg-transparent border border-gray-300 text-gray-500 text-[14px] rounded-[12px]">
                        <span class="vazir text-sm">بدون موجودی</span>
                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/general-reports.blade.php ENDPATH**/ ?>