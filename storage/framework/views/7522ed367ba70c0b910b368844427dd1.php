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
            <h1 class="text-[24px] font-medium vazir">بررسی معاملات ویرایش / حذف شده</h1>
            <h1 class="text-[#8C8C8C]">تمام  معاملاتی که ویرایش یا حذف شده</h1>
        </div>

        <hr class="my-6 border-t border-[#D9D9D9] w-full">

        <!-- جدول تراکنش‌های حذف/ویرایش شده -->
        <div class="w-full">
            <div class="bg-[#F5F5F5] p-6 rounded-[12px]"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <!-- فیلتر و جستجو -->
                <div class="flex items-center justify-between mt-5 gap-3 w-full">


                      <!-- دکمه فیلتر -->
                    <div class="relative">
                        <button wire:click="$toggle('filterOpen')"
                            class="px-10 py-3 border rounded-lg bg-[#2563EB] transition flex items-center gap-2 text-white">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/filter.svg')); ?>" alt="">
                            <span class="text-white">فیلتر</span>
                        </button>

                        <!--[if BLOCK]><![endif]--><?php if($filterOpen): ?>
                        <div
                            class="absolute top-full mt-2 bg-white border rounded-xl shadow-lg p-4 w-72 z-50 flex flex-col gap-3">
                            <select wire:model="filterAction" class="border rounded px-3 py-2 w-full">
                                <option value="">همه اقدامات</option>
                                <option value="حذف">حذف شده</option>
                                <option value="updated">ویرایش شده</option>
                            </select>

                            <select wire:model="filterDocumentType" class="border rounded px-3 py-2 w-full">
                                <option value="">همه انواع سند</option>
                                <option value="transactions">تراکنش‌ها</option>
                                <option value="transferinaccount">انتقال به حساب</option>
                                <option value="conversion_transfer">تبدیل ارز</option>
                                <option value="account_to_account">حساب به حساب</option>
                                <option value="cash_exchange">صرافی نقدی</option>
                                <option value="remittance">حواله</option>
                            </select>

                            <button wire:click="applyFilter"
                                class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 w-full">
                                اعمال فیلتر
                            </button>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>


                    <!-- جستجو -->
                    <div class="relative w-[800px]">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5">
                        <input type="text" wire:model.debounce.500ms="search" placeholder="جستجو بر اساس شرح سند..."
                            class="w-full border border-gray-300 bg-transparent rounded-2xl pl-10 pr-3 py-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm">
                    </div>

                    
                  

                </div>

                <div class="overflow-x-auto w-full mt-4">
                    <div class="max-h-[600px] overflow-y-auto">
                        <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500">
                            <thead class="bg-[#2B65E5] text-white text-[16px] vazir h-16 sticky top-0">
                                <tr>
                                    <th class="px-4 py-4 font-bold w-16">
                                        <span class="border border-white px-2 py-1 rounded-lg">#</span>
                                    </th>
                                    <th class="px-4 py-4 font-bold">نوع اقدام</th>
                                    <th class="px-4 py-4 font-bold">تاریخ اقدام</th>
                                    <th class="px-4 py-4 font-bold">سند</th>
                                    <th class="px-4 py-4 font-bold">شرح سند</th>
                                    <th class="px-4 py-4 font-bold">کاربر ثبت کننده</th>
                                    <th class="px-4 py-4 font-bold">کاربر ویرایش کننده / حذف کننده</th>
                                    <th class="px-4 py-4 font-bold text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $trashRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent hover:bg-gray-50">
                                    <td class="px-4 py-4 vazir text-[16px] font-medium text-center">
                                        <?php echo e($trashRecords->firstItem() + $key); ?>

                                    </td>

                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <!--[if BLOCK]><![endif]--><?php if($record->action == 'حذف'): ?>
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-lg text-sm">
                                            حذف شده
                                        </span>
                                        <?php else: ?>
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-lg text-sm">
                                            ویرایش شده
                                        </span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>

                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <?php echo e(jdate($record->created_at)->format('Y/m/d H:i')); ?>

                                    </td>

                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <?php echo e($this->getDocumentTypeLabel($record->document_type)); ?>

                                    </td>

                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <?php echo e($record->document_discription); ?>

                                    </td>

                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <!--[if BLOCK]><![endif]--><?php if($record->registeredUser): ?>
                                        <?php echo e($record->registeredUser->name); ?>

                                        <?php else: ?>
                                        <span class="text-gray-400">نامشخص</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>

                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <!--[if BLOCK]><![endif]--><?php if($record->user): ?>
                                        <?php echo e($record->user->name); ?>

                                        <?php elseif($record->admin): ?>
                                        <?php echo e($record->admin->name); ?>

                                        <?php else: ?>
                                        <span class="text-gray-400">سیستم</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>

                                    <td class="px-4 py-4  flex gap-3 vazir text-[16px] font-medium text-center">
                                        <button wire:click="showDetails(<?php echo e($record->id); ?>)"
                                            class="bg-white/10  text-white px-3 py-2 rounded-lg text-sm transition">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/eye.svg')); ?>" alt="مشاهده جزئیات"
                                                class="w-8 h-8">
                                        </button>


                                        <button wire:click="confirmDelete(<?php echo e($record->id); ?>)"
                                            class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                            title="حذف">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>"
                                                class="w-8 h-8" alt="Delete">
                                        </button>

                                        <!-- مودال تأیید حذف -->
                                        <!--[if BLOCK]><![endif]--><?php if($confirmDeleteId): ?>
                                        <div
                                            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-10 z-50">
                                            <div
                                                class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px]  rounded-[12px] shadow-xl w-[653px] h-[239.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
                                                <button wire:click="$set('confirmDeleteId', null)"
                                                    class="flex right-0 h-4 w-4"><img
                                                        src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>"
                                                        alt=""></button>
                                                <h1 class="text-2xl text-black shabnam font-medium leading-[100%] ">
                                                    حذف ترانزکشــــــــــن</h1>
                                                <hr class="bg-[#E1DED3] mt-8">
                                                <p class=" mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این
                                                    ترانزکشن را حذف کنید؟</p>
                                                <div class="flex justify-center gap-4">
                                                    <button wire:click="$set('confirmDeleteId', null)"
                                                        class="px-20  text-white text-xl shabnam-fd py-3 bg-[#DD2424] rounded-xl transition">
                                                        <?php echo e(__('messages.no')); ?>

                                                    </button>
                                                    <button wire:click="deleteConfirmed"
                                                        class="px-20 py-3 bg-[#2563EB] text-xl shabnam-fd text-white rounded-xl  transition flex items-center gap-2">
                                                        <?php echo e(__('messages.yes')); ?>

                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                    </td>


                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-gray-500 py-8 text-lg">
                                        هیچ سند حذف یا ویرایش شده‌ای وجود ندارد
                                    </td>
                                </tr>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <!--[if BLOCK]><![endif]--><?php if($trashRecords->hasPages()): ?>
                    <div class="mt-4">
                        <?php echo e($trashRecords->links()); ?>

                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>

    <!--[if BLOCK]><![endif]--><?php if($showDetailsModal): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50 backdrop-blur-sm">
        <div
            class="bg-white p-6 rounded-2xl shadow-2xl w-11/12 max-w-6xl max-h-[90vh] overflow-y-auto border border-gray-100">
            <!-- هدر -->
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#D9D9D9]">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 vazir">جزئیات تغییرات</h2>
                    <p class="text-[#8C8C8C] text-sm mt-1 vazir">مشاهده تاریخچه ویرایش و حذف</p>
                </div>
                <button wire:click="closeDetails" class="p-2 hover:bg-gray-100 rounded-lg transition-all duration-200">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- اطلاعات کلی به صورت جدول -->
            <div class="mb-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="bg-[#2B65E5] text-white text-[16px] vazir h-16">
                            <tr>
                                <th class="px-4 py-4 font-bold">نوع سند</th>
                                <th class="px-4 py-4 font-bold">نوع اقدام</th>
                                <th class="px-4 py-4 font-bold">تاریخ اقدام</th>
                                <th class="px-4 py-4 font-bold">کاربر تغییر دهنده</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-black border-b border-[#D9D9D9] bg-transparent hover:bg-gray-50">
                                <td class="px-4 py-4 vazir text-[16px] font-medium">
                                    <?php echo e($this->getDocumentTypeLabel($selectedRecord->document_type ?? '')); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[16px] font-medium">
                                    <!--[if BLOCK]><![endif]--><?php if(($selectedRecord->action ?? '') == 'حذف'): ?>
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-lg text-sm">
                                        حذف شده
                                    </span>
                                    <?php else: ?>
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-lg text-sm">
                                        ویرایش شده
                                    </span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <td class="px-4 py-4 vazir text-[16px] font-medium">
                                    <?php echo e(jdate($selectedRecord->created_at ?? now())->format('Y/m/d H:i')); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[16px] font-medium">
                                    <!--[if BLOCK]><![endif]--><?php if($selectedRecord->user ?? false): ?>
                                    <?php echo e($selectedRecord->user->name); ?>

                                    <?php elseif($selectedRecord->admin ?? false): ?>
                                    <?php echo e($selectedRecord->admin->name); ?>

                                    <?php else: ?>
                                    <span class="text-gray-400">سیستم</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- شرح سند -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-[#D9D9D9]">
                <div class="text-sm text-gray-600 vazir mb-2 font-bold">شرح سند:</div>
                <div class="text-gray-800 vazir text-[16px]"><?php echo e($selectedRecord->document_discription ?? 'بدون شرح'); ?>

                </div>
            </div>

            <?php if(($selectedRecord->action ?? '') == 'ویرایش'): ?>

            <div class="space-y-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-1 h-6 bg-blue-500 rounded-full"></div>
                    <h3 class="text-lg font-bold text-gray-800 vazir">تغییرات انجام شده</h3>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    <!-- داده‌های قدیمی -->
                    <div class="border border-[#D9D9D9] rounded-lg overflow-hidden bg-white">
                        <div class="bg-red-500 p-4">
                            <h4 class="text-white vazir font-bold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                داده‌های قبلی
                            </h4>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 border-b border-[#D9D9D9]">
                                    <tr>
                                        <th class="px-4 py-3 text-right vazir font-bold text-gray-700">فیلد</th>
                                        <th class="px-4 py-3 text-right vazir font-bold text-gray-700">مقدار</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->formatDataForDisplay($selectedRecord->old_data ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>
                                    $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-b border-[#D9D9D9] hover:bg-gray-50">
                                        <td class="px-4 py-3 vazir text-gray-700 font-medium bg-gray-50">
                                            <?php echo e($this->getFieldLabel($key)); ?>

                                        </td>
                                        <td class="px-4 py-3 vazir text-gray-800">
                                            <?php echo $value; ?>

                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- داده‌های جدید -->
                    <div class="border border-[#D9D9D9] rounded-lg overflow-hidden bg-white">
                        <div class="bg-green-500 p-4">
                            <h4 class="text-white vazir font-bold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                داده‌های جدید
                            </h4>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 border-b border-[#D9D9D9]">
                                    <tr>
                                        <th class="px-4 py-3 text-right vazir font-bold text-gray-700">فیلد</th>
                                        <th class="px-4 py-3 text-right vazir font-bold text-gray-700">مقدار</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->formatDataForDisplay($selectedRecord->new_data ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>
                                    $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-b border-[#D9D9D9] hover:bg-gray-50">
                                        <td class="px-4 py-3 vazir text-gray-700 font-medium bg-gray-50">
                                            <?php echo e($this->getFieldLabel($key)); ?>

                                        </td>
                                        <td class="px-4 py-3 vazir text-gray-800">
                                            <?php echo $value; ?>

                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- نمایش داده‌های حذف شده -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-1 h-6 bg-red-500 rounded-full"></div>
                    <h3 class="text-lg font-bold text-gray-800 vazir">داده‌های حذف شده</h3>
                </div>

                <div class="border border-[#D9D9D9] rounded-lg overflow-hidden bg-white">
                    <div class="bg-red-500 p-4">
                        <h4 class="text-white vazir font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            اطلاعات حذف شده
                        </h4>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b border-[#D9D9D9]">
                                <tr>
                                    <th class="px-4 py-3 text-right vazir font-bold text-gray-700">فیلد</th>
                                    <th class="px-4 py-3 text-right vazir font-bold text-gray-700">مقدار</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $this->formatDataForDisplay($selectedRecord->old_data ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b border-[#D9D9D9] hover:bg-gray-50">
                                    <td class="px-4 py-3 vazir text-gray-700 font-medium bg-gray-50">
                                        <?php echo e($this->getFieldLabel($key)); ?>

                                    </td>
                                    <td class="px-4 py-3 vazir text-gray-800">
                                        <?php echo $value; ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- فوتر -->
            <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-[#D9D9D9]">
                <button wire:click="closeDetails"
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg vazir hover:bg-gray-600 transition font-medium">
                    بستن
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/trash-edit.blade.php ENDPATH**/ ?>