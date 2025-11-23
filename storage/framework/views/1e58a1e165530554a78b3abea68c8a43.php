<div class="px-5">
    <!-- دکمه‌های دسته‌بندی -->
    <div class="grid grid-cols-1 w-[1200px] md:grid-cols-4 lg:grid-cols-4 gap-3 mb-5">
        <button wire:click="selectCategory('customers')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارشات مالی
            مشتریان</button>
        <button wire:click="selectCategory('accounts')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارش حسابات و
            صندوق</button>
        <button wire:click="selectCategory('transactions')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارشات تراکنش های
            معاملات</button>
        <button wire:click="selectCategory('management')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارشات مدیریتی و
            تحلیلی</button>
    </div>


    <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <!-- select زیرشاخه -->
        <!--[if BLOCK]><![endif]--><?php if($selectedCategory): ?>
        <div class="mb-5 relative w-full">
            <select wire:model.live="selectedSubCategory"
                class="border bg-transparent border-[#8C8C8C] rounded-[12px] px-4 py-2 pt-[13px] pr-[9px] pl-[9px] pb-[13px] w-full appearance-none">
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


        <!-- بخش جدول -->
        <div class="overflow-x-auto w-full mt-4 mb-8">
            <div class="flex flex-col max-h-[600px] overflow-y-auto">
                <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500">
                    <thead class="bg-[#2B65E5] text-white text-[16px] vazir h-16 sticky top-0">
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
                            <?php
                            $latestExchangeRate = \App\Models\Sarafi\ExchangeRates::latest()->first();
                            $sourceCurrency = $latestExchangeRate->source_currency ?? 'دالر';
                            ?>
                            <th class="px-4 py-4 font-bold">بیلانس به <?php echo e($sourceCurrency); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 rounded-lg"><?php echo e($index + 1); ?></span>
                            </td>
                            <td class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap"><?php echo e($report['account_number']); ?></td>
                            <td class="px-4 py-4"><?php echo e($report['fullname']); ?></td>
                            <td class="px-3 py-4">
                                <?php echo e($report['last_date'] ? \Carbon\Carbon::parse($report['last_date'])->format('Y/m/d') :
                                '-'); ?>

                            </td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['usd'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['afn'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['irr'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['pkr'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['eur'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['aed'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['try'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['cny'] ?? 0, 2)); ?></td>
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
          <div>
            سلام
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




    <!--[if BLOCK]><![endif]--><?php if($selectedSubCategory == 'گزارش بیلانس مشتریان'): ?>
    <!-- بخش انتخاب مشتری و نمایش نمودار (در یک div جداگانه) -->
    <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <div class="flex w-full">
            <div class="md:w-1/2">
                <div class="flex-1">
                    <div class="relative w-[589px]">
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
                <!--[if BLOCK]><![endif]--><?php if($selectedCustomerId && count($currencyPercentages) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6 mt-6 w-[589px]">
                    <div class="space-y-4">
                        <div
                            class="w-full h-[79px] flex flex-col md:flex-row items-center justify-between p-6 bg-[#2563EB] text-white text-[16px] rounded-[12px]">
                            <p class="vazir font-bold">ارزش کل موجودی</p>
                            <p class="vazir font-bold">
                                <?php
                                $totalUSD = 0;
                                foreach($selectedCustomerBalance as $balance) {
                                $totalUSD += $balance['balance_usd'];
                                }
                                ?>
                                <?php echo e(number_format($totalUSD, 2)); ?>

                                <span>دالر</span>
                            </p>
                        </div>

                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currencyPercentages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyCode => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div
                            class="w-full h-[79px] flex flex-col md:flex-row items-center justify-between p-6 bg-transparent border border-[#2563EB] text-black text-[16px] rounded-[12px]">
                            <div class="flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full" style="background-color: <?php echo e($data['color']); ?>">
                                </div>
                                <span class="vazir font-bold"><?php echo e($data['currency_name']); ?></span>
                            </div>
                            <div class="text-left">
                                <p class="vazir font-bold"><?php echo e(number_format($data['balance'], 2)); ?></p>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <?php elseif($selectedCustomerId): ?>
                <div class="text-center py-8">
                    <p class="text-gray-500 vazir">این مشتری موجودی ندارد</p>
                </div>
                <?php else: ?>
                <div class="text-center py-8">
                    <p class="text-gray-500 vazir">لطفاً یک مشتری انتخاب کنید</p>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- بخش نمودار SVG -->
            <!--[if BLOCK]><![endif]--><?php if($selectedCustomerId && count($currencyPercentages) > 0): ?>
            <div class="md:w-1/2 mt-6">
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
                    <div class="relative w-[300px] h-[300px] mx-auto">
                        <svg width="400" height="400" viewBox="0 0 40 40">
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

                            <text x="<?php echo e($textX); ?>" y="<?php echo e($textY); ?>" font-size="2.7" fill="white" text-anchor="middle"
                                alignment-baseline="middle"
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
                    <div class="absolute top-9 align-middle flex flex-col justify-center items-center gap-6">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $chartData['labels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 rounded-full shadow-sm"
                                style="background: linear-gradient(135deg, <?php echo e($this->lightenColor($chartData['colors'][$index], 30)); ?>, <?php echo e($this->darkenColor($chartData['colors'][$index], 20)); ?>);">
                            </div>
                            <span class="text-sm vazir text-gray-700">
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


 </div>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/general-reports.blade.php ENDPATH**/ ?>