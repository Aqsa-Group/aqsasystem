<div>
    <?php if(session()->has('message')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-gradient-to-br from-indigo-400 to-indigo-500 vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e(session('message')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session()->has('error')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-red-500 vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e(session('error')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?>

    <!-- کارت‌های آماری برداشت -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-4">
        <!-- کارت ۱: برداشت‌های امروز -->
        <div
            class="bg-gradient-to-br from-rose-100 to-rose-200 border-l-4 border-rose-500 text-rose-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">برداشت‌های امروز</h3>
                <div class="bg-rose-500 p-2 rounded-full">
                    <i class="fa-solid fa-calendar-day text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rose-700">افغانی:</span>
                    <span class="text-lg font-bold"><?php echo e(number_format($withdrawalStats['today']['afn'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rose-700">دالر:</span>
                    <span class="text-lg font-bold"><?php echo e(number_format($withdrawalStats['today']['usd'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rose-700">تومان:</span>
                    <span class="text-lg font-bold"><?php echo e(number_format($withdrawalStats['today']['toman'])); ?></span>
                </div>
            </div>
        </div>

        <!-- کارت ۲: برداشت‌های این هفته -->
        <div
            class="bg-gradient-to-br from-green-100 to-green-200 border-l-4 border-green-500 text-green-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">برداشت‌های این هفته</h3>
                <div class="bg-green-500 p-2 rounded-full">
                    <i class="fa-solid fa-calendar-week text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-green-700">افغانی:</span>
                    <span class="text-lg font-bold"><?php echo e(number_format($withdrawalStats['week']['afn'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-green-700">دالر:</span>
                    <span class="text-lg font-bold"><?php echo e(number_format($withdrawalStats['week']['usd'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-green-700">تومان:</span>
                    <span class="text-lg font-bold"><?php echo e(number_format($withdrawalStats['week']['toman'])); ?></span>
                </div>

            </div>
        </div>

        <!-- کارت ۳: برداشت‌های این ماه -->
        <div
            class="bg-gradient-to-br from-blue-100 to-blue-200 border-l-4 border-blue-500 text-blue-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">برداشت‌های این ماه</h3>
                <div class="bg-blue-500 p-2 rounded-full">
                    <i class="fa-solid fa-calendar-alt text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-700">افغانی:</span>
                    <span class="text-lg font-bold"><?php echo e(number_format($withdrawalStats['month']['afn'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-700">دالر:</span>
                    <span class="text-lg font-bold"><?php echo e(number_format($withdrawalStats['month']['usd'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-700">تومان:</span>
                    <span class="text-lg font-bold"><?php echo e(number_format($withdrawalStats['month']['toman'])); ?></span>
                </div>

            </div>
        </div>

        <!-- کارت ۴: برداشت‌های کلی -->
        <div
            class="bg-gradient-to-br from-purple-100 to-purple-200 border-l-4 border-purple-500 text-purple-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">برداشت‌های کلی</h3>
                <div class="bg-purple-500 p-2 rounded-full">
                    <i class="fa-solid fa-chart-pie text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-purple-700">افغانی:</span>
                    <span class="text-lg font-bold"><?php echo e(number_format($withdrawalStats['total']['afn'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-purple-700">دالر:</span>
                    <span class="text-lg font-bold"><?php echo e(number_format($withdrawalStats['total']['usd'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-purple-700">تومان:</span>
                    <span class="text-lg font-bold"><?php echo e(number_format($withdrawalStats['total']['toman'])); ?></span>
                </div>

            </div>
        </div>
    </div>

    
    <div class="flex flex-col lg:flex-row gap-10 mt-4 p-4">

        
        <div class="flex flex-col bg-[#F5F5F5] w-full lg:w-[574px] p-[12px] h-[620px] rounded-[12px] space-y-2"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

            
            <div class="flex flex-row justify-between p-[16px] border border-[#8C8C8C] rounded-[12px] flex-wrap mb-5">
                <p class="flex justify-center items-center text-center">
                    <img src="<?php echo e(asset('assets/sarafi/all_icon/edit-2.svg')); ?>" alt="" class="h-6 w-6">
                    <?php echo e($withdrawalId ? 'فورم ویرایش برداشت' : 'فورم ثبت برداشت از صندوق'); ?>

                </p>
            </div>

            
            <form wire:submit.prevent="submitWithdrawal" class="space-y-6">

                <!-- انتخاب ارز -->

                <div class="flex flex-col lg:flex-row gap-4">

                    <div class="flex-1">
                        <label class="block text-[16px] font-medium text-black mb-2 vazir">نوع برداشت</label>
                        <div class="relative w-full">
                            <select wire:model="type"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="afn">کرایه</option>
                                <option value="usd">پول برق</option>
                                <option value="toman">غذا</option>
                                <option value="toman">تعمیرات</option>
                                <option value="toman">خرید لوازم</option>
                                <option value="toman">خرید جنس</option>
                                <option value="toman">متفرقه</option>
                            </select>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓">
                            </div>
                        </div>
                        <?php $__errorArgs = ['type'];
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

                    <div class="flex-1">
                        <label class="block text-[16px] font-medium text-black mb-2 vazir">انتخاب ارز</label>
                        <div class="relative w-full">
                            <select wire:model="currency"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="afn">افغانی</option>
                                <option value="usd">دالر</option>
                                <option value="toman">تومان</option>
                            </select>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓">
                            </div>
                        </div>
                        <?php $__errorArgs = ['currency'];
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



                <!-- مقدار و تاریخ -->
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-[16px] font-medium text-black mb-2 vazir">مقدار</label>
                        <div class="relative w-full">
                            <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">
                                <?php if($currency === 'afn'): ?> افغانی
                                <?php elseif($currency === 'usd'): ?> دالر
                                <?php else: ?> تومان
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php $__errorArgs = ['amount'];
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

                    <div class="lg:w-[290px]">
                        <label class="block text-[16px] font-medium text-black mb-2 vazir">تاریخ</label>
                        <input type="text" id="datePicker" wire:model="date" wire:ignore placeholder="YYYY/MM/DD"
                            class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 cursor-pointer" />
                    </div>
                </div>

                <!-- توضیحات -->
                <div>
                    <label class="block text-[16px] font-medium text-black mb-2 vazir">شرح برداشت</label>
                    <textarea wire:model="description" rows="3" placeholder="توضیحات برداشت از صندوق..."
                        class="w-full p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    <?php $__errorArgs = ['description'];
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

                <!-- دکمه‌ها -->
                <div class="flex gap-4 p-4 justify-center items-center text-center flex-wrap">
                    <button type="submit"
                        class="bg-[#61B138] text-[18px] vazir font-semibold rounded-[8px] px-20 py-3 text-white">
                        <?php echo e($withdrawalId ? 'بروزرسانی' : 'ثبت برداشت'); ?>

                    </button>

                    <button type="button" wire:click="resetForm"
                        class="bg-[#DD2424] text-[18px] vazir font-semibold rounded-[8px] px-20 py-3 text-white">
                        <?php echo e($withdrawalId ? 'لغو ویرایش' : 'انصراف'); ?>

                    </button>
                </div>
            </form>
        </div>

        
        <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px]"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

            
            <div
                class="flex flex-col md:flex-row justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                <h1 class="text-lg md:text-xl lg:text-2xl vazir">برداشت‌های ثبت شده از صندوق</h1>
            </div>

            
            <div class="overflow-x-auto w-full">
                <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                    <table
                        class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead
                            class="bg-gradient-to-br from-indigo-400 to-indigo-500 dark:bg-gray-700 text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0">
                            <tr>
                                <th class="px-4 py-4 font-bold w-16">#</th>
                                <th class="px-4 py-4 font-bold w-32">نوع برداشت</th>
                                <th class="px-4 py-4 font-bold w-32">ارز</th>
                                <th class="px-4 py-4 font-bold w-40">مبلغ</th>
                                <th class="px-4 py-4 font-bold w-80 text-center">توضیحات</th>
                                <th class="px-4 py-4 font-bold w-40">تاریخ</th>
                                <th class="px-4 py-4 font-bold w-48 text-center">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $withdrawal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                    <?php echo e($key + 1); ?>

                                </td>

                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                    <?php echo e($withdrawal->type); ?>

                                </td>

                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                    <?php if($withdrawal->currency === 'afn'): ?>
                                    <span class="bg-rose-100 text-rose-800 px-3 py-1 rounded-full">افغانی</span>
                                    <?php elseif($withdrawal->currency === 'usd'): ?>
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full">دالر</span>
                                    <?php else: ?>
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">تومان</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-40">
                                    <?php echo e(number_format($withdrawal->amount)); ?>

                                    <span class="text-sm text-gray-500">
                                        <?php if($withdrawal->currency === 'afn'): ?> افغانی
                                        <?php elseif($withdrawal->currency === 'usd'): ?> دالر
                                        <?php else: ?> تومان
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                    <?php echo e($withdrawal->description ?? 'بدون توضیح'); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                    <div class="whitespace-nowrap">
                                        <div class="font-medium">
                                            <?php echo e(explode(' ', $withdrawal->date)[0]); ?>

                                        </div>
                                        <div class="text-gray-500 text-sm mt-1">
                                            <?php echo e(\Carbon\Carbon::parse($withdrawal->created_at)->format('h:i A')); ?>

                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 text-center w-[68]">
                                    <div class="flex justify-center gap-3">
                                        <button wire:click="edit(<?php echo e($withdrawal->id); ?>)"
                                            class="w-12 h-12 flex items-center justify-center rounded-full transition-colors hover:bg-gray-100"
                                            title="ویرایش">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>"
                                                class="w-7 h-7" alt="Edit">
                                        </button>

                                        <button wire:click="confirmDelete(<?php echo e($withdrawal->id); ?>)"
                                            class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                            title="حذف">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>"
                                                class="w-8 h-8" alt="Delete">
                                        </button>


                                        <button wire:click="print(<?php echo e($withdrawal->id); ?>)"
                                            class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                            title="پرینت">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>"
                                                class="w-10 h-10" alt="Print">
                                        </button>


                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-8 text-lg">
                                    هیچ برداشتی یافت نشد
                                </td>
                            </tr>
                            <?php endif; ?>

                            
                            <?php if($confirmDeleteId): ?>
                            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                <div
                                    class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] rounded-[12px] shadow-xl w-[653px] h-[239.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
                                    <button wire:click="$set('confirmDeleteId', null)"
                                        class="absolute left-0 right-4 top-4 h-6 w-6 flex items-center justify-center">
                                        <img src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>" alt="بستن"
                                            class="w-4 h-4">
                                    </button>
                                    <h1 class="text-2xl text-black shabnam font-medium leading-[100%] mt-2">حذف برداشت
                                        از صندوق</h1>
                                    <hr class="bg-[#E1DED3] mt-4 mx-4">
                                    <p class="mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این برداشت را حذف
                                        کنید؟</p>
                                    <div class="flex justify-center gap-4">
                                        <button wire:click="$set('confirmDeleteId', null)"
                                            class="px-12 text-white text-lg shabnam-fd py-3 bg-[#DD2424] rounded-xl transition hover:bg-red-700">خیر</button>
                                        <button wire:click="deleteConfirmed"
                                            class="px-12 py-3 bg-gradient-to-br from-indigo-400 to-indigo-500 text-lg shabnam-fd text-white rounded-xl transition hover:bg-blue-700 flex items-center gap-2">بلی</button>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function() {
            if (typeof kamaDatepicker !== 'undefined') {
                kamaDatepicker('datePicker', {
                    buttonsColor: "blue",
                    forceFarsiDigits: true,
                    markToday: true,
                    markHolidays: true,
                    gotoToday: true,
                    highlightSelectedDay: true
                });
            }
        });
    </script>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/withdrawal.blade.php ENDPATH**/ ?>