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
            <h1 class="text-[24px] font-medium vazir">مدیریت تایید حواله‌ها</h1>
            <h1 class="text-[#8C8C8C]">صفحه تایید و رد حواله‌های بانکی</h1>
        </div>
        
        <hr class="my-6 border-t border-[#D9D9D9] w-full">

        <!-- جدول تایید حواله‌ها -->
        <div class="w-full">
            <div class="bg-[#F5F5F5] p-6 rounded-[12px]" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                
                <div class="flex flex-col md:flex-row justify-between items-center border border-[#8C8C8C] p-4 rounded-[12px] mb-4 gap-3">
                    <h1 class="text-xl md:text-2xl vazir">حواله‌های در انتظار تایید</h1>
                    <div class="text-gray-600 vazir">
                        تعداد: <?php echo e(count($pendingApprovals)); ?> حواله
                    </div>
                </div>

                <div class="overflow-x-auto w-full">
                    <div class="max-h-[600px] overflow-y-auto">
                        <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="bg-[#2B65E5] dark:bg-gray-700 text-white text-[16px] vazir h-16 sticky top-0">
                                <tr>
                                    <th class="px-4 py-4 font-bold w-16">
                                        <span class="border border-white px-2 py-1 rounded-lg">#</span>
                                    </th>
                                    <th class="px-4 py-4 font-bold">فرستنده</th>
                                    <th class="px-4 py-4 font-bold">گیرنده</th>
                                    <th class="px-4 py-4 font-bold">مبلغ</th>
                                    <th class="px-4 py-4 font-bold">واحد</th>
                                    <th class="px-4 py-4 font-bold">کد رهگیری</th>
                                    <th class="px-4 py-4 font-bold">تاریخ ثبت</th>
                                    <th class="px-4 py-4 font-bold text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $pendingApprovals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $approval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent hover:bg-gray-50">
                                    <td class="px-4 py-4 vazir text-[16px] font-medium text-center">
                                        <?php echo e($key + 1); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <div class="space-y-1">
                                            <p class="font-semibold"><?php echo e($approval->customer->fullname ?? '-'); ?></p>
                                            <p class="text-sm text-gray-500"><?php echo e($approval->source_account); ?></p>
                                            <p class="text-xs text-gray-400"><?php echo e($approval->from_bank); ?></p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <div class="space-y-1">
                                            <p class="font-semibold"><?php echo e($approval->recipient->fullname ?? $approval->giver_name); ?></p>
                                            <p class="text-sm text-gray-500"><?php echo e($approval->to_bank); ?></p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <?php echo e(number_format($approval->amount)); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <?php
                                            $currencies = [
                                                ['code' => 'usd', 'name_fa' => 'دالر'],
                                                ['code' => 'afn', 'name_fa' => 'افغانی'],
                                                ['code' => 'eur', 'name_fa' => 'یورو'],
                                                ['code' => 'irr', 'name_fa' => 'تومان'],
                                                ['code' => 'aed', 'name_fa' => 'درهم'],
                                                ['code' => 'try', 'name_fa' => 'لیره'],
                                                ['code' => 'cny', 'name_fa' => 'یوان'],
                                                ['code' => 'pkr', 'name_fa' => 'کلدار'],
                                                ['code' => 'gbp', 'name_fa' => 'پوند'],
                                                ['code' => 'jpy', 'name_fa' => 'ین'],
                                                ['code' => 'sar', 'name_fa' => 'ریال سعودی'],
                                                ['code' => 'inr', 'name_fa' => 'روپیه'],
                                            ];
                                            $currencyName = collect($currencies)->firstWhere('code', $approval->currency)['name_fa'] ?? $approval->currency;
                                        ?>
                                        <?php echo e($currencyName); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <code class="bg-gray-100 px-2 py-1 rounded"><?php echo e($approval->tracking_code); ?></code>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium"><?php echo e($approval->created_at->format('Y/m/d')); ?></div>
                                            <div class="text-gray-500 text-sm"><?php echo e($approval->created_at->format('H:i:s')); ?></div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button wire:click="confirmApprove(<?php echo e($approval->id); ?>)" 
                                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm vazir transition-colors flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                تایید
                                            </button>
                                            
                                            <button wire:click="confirmReject(<?php echo e($approval->id); ?>)" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm vazir transition-colors flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                رد
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-gray-500 py-8 text-lg">
                                        هیچ حواله‌ای در انتظار تایید وجود ندارد
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

    <!-- Modal تایید -->
    <!--[if BLOCK]><![endif]--><?php if($confirmApproveId): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-[12px] shadow-xl w-96">
            <h2 class="text-xl vazir font-bold mb-4">تایید حواله</h2>
            <p class="vazir mb-4">آیا از تایید این حواله اطمینان دارید؟</p>
            
            <textarea wire:model="approvalNotes" 
                      placeholder="یادداشت تایید (اختیاری)"
                      class="w-full p-3 border border-gray-300 rounded-lg mb-4 vazir"
                      rows="3"></textarea>
            
            <div class="flex justify-end gap-3">
                <button wire:click="cancelAction" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg vazir">
                    انصراف
                </button>
                <button wire:click="approveRemittance" 
                        class="px-4 py-2 bg-green-500 text-white rounded-lg vazir">
                    تایید
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Modal رد -->
    <!--[if BLOCK]><![endif]--><?php if($confirmRejectId): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-[12px] shadow-xl w-96">
            <h2 class="text-xl vazir font-bold mb-4">رد حواله</h2>
            <p class="vazir mb-4">آیا از رد این حواله اطمینان دارید؟</p>
            
            <textarea wire:model="approvalNotes" 
                      placeholder="دلیل رد (اختیاری)"
                      class="w-full p-3 border border-gray-300 rounded-lg mb-4 vazir"
                      rows="3"></textarea>
            
            <div class="flex justify-end gap-3">
                <button wire:click="cancelAction" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg vazir">
                    انصراف
                </button>
                <button wire:click="rejectRemittance" 
                        class="px-4 py-2 bg-red-500 text-white rounded-lg vazir">
                    رد
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/remittance-approval.blade.php ENDPATH**/ ?>