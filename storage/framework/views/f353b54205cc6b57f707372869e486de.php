<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-gradient-to-br from-indigo-400 to-indigo-500 vazir">
        <div class="h-[60px] md:h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[14px] md:text-[18px]">
                <?php echo e(session('message')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="flex flex-col md:flex-row items-center gap-3 md:gap-4 mb-4  md:mb-6 mr-4 md:mr-10">
        <!-- دکمه افزودن مشتری جدید -->
        <button wire:click="createCustomer"
            class="flex items-center justify-center rounded-xl w-full md:w-[218px] h-[50px] md:h-[54px] bg-gradient-to-br from-indigo-400 to-indigo-500 text-white hover:bg-gradient-to-br from-indigo-400 to-indigo-500 text-[14px] md:text-base">
            <img src="<?php echo e(asset('assets/sarafi/all_icon/user-add.svg')); ?>" alt="Add" class="w-[24px] h-[24px] md:w-[30px] md:h-[30px] me-2">
            <?php echo e(__('messages.add_customer')); ?>

        </button>

        <!-- 🔍 Search -->
        <div class="relative w-full md:w-auto">
            <input type="text" placeholder="<?php echo e(__('messages.search_customer')); ?>"
                class="border border-[#8C8C8C] placeholder:text-[#8C8C8C] vazir rounded-xl w-full md:w-[329px] h-[50px] md:h-[54px] pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500 text-[14px] md:text-base">
            <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                class="h-5 w-5 md:h-6 md:w-6 absolute left-2 bottom-3 md:bottom-4">
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-full mr-0 md:mr-4 bg-[#F5F5F5] dark:bg-gray-900 max-w-[92vw] mx-auto"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

        <table class="min-w-[800px] md:min-w-full text-sm text-gray-700 dark:text-gray-400 text-center border-collapse">
            <!-- 🧭 Table Header -->
            <thead class="bg-gradient-to-br from-indigo-400 to-indigo-500 dark:bg-gray-700 text-white text-[14px] md:text-[18px] vazir h-12 md:h-14">
                <tr>
                    <th class="px-3 md:px-6 py-2 md:py-3 font-semibold text-right">نام کامل</th>
                    <th class="px-3 md:px-6 py-2 md:py-3 font-semibold">شماره حساب</th>
                    <th class="px-3 md:px-6 py-2 md:py-3 font-semibold">شهر</th>
                    <th class="px-3 md:px-6 py-2 md:py-3 font-semibold">شماره تماس</th>
                    <th class="px-3 md:px-6 py-2 md:py-3 font-semibold">تذکره</th>
                    <th class="px-3 md:px-6 py-2 md:py-3 font-semibold text-center">عملیات</th>
                </tr>
            </thead>

            <!-- 📊 Table Body -->
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <!-- نام -->
                    <td class="px-3 md:px-6 py-3 md:py-4 text-right flex items-center gap-2 md:gap-3">
                        <img class="w-8 h-8 md:w-10 md:h-10 rounded-full"
                            src="<?php echo e($customer->image ? asset('storage/'.$customer->image) : 'https://ui-avatars.com/api/?name='.urlencode($customer->fullname)); ?>"
                            alt="<?php echo e($customer->fullname); ?>">
                        <span class="text-[14px] md:text-lg text-gray-900 dark:text-white"><?php echo e($customer->fullname); ?></span>
                    </td>

                    <!-- شماره حساب -->
                    <td class="px-3 md:px-6 py-3 md:py-4 text-[14px] md:text-[16px] text-black vazir"><?php echo e($customer->account_number ?? '-'); ?></td>

                    <!-- شهر -->
                    <td class="px-3 md:px-6 py-3 md:py-4 text-[14px] md:text-[16px] text-black vazir"><?php echo e($customer->city ?? '-'); ?></td>

                    <!-- تلفن -->
                    <td class="px-3 md:px-6 py-3 md:py-4 text-[14px] md:text-[16px] text-black vazir"><?php echo e($customer->phone ?? '-'); ?></td>

                    <!-- تذکره -->
                    <td class="px-3 md:px-6 py-3 md:py-4 text-[14px] md:text-[16px] text-black vazir"><?php echo e($customer->idcard_number ?? '-'); ?></td>

                    <!-- عملیات -->
                    <td class="px-3 md:px-6 py-3 md:py-4 flex justify-center items-center gap-2 md:gap-3">
                        <!-- ویرایش -->
                        <button wire:click="editCustomer(<?php echo e($customer->id); ?>)"
                            class="hover:scale-110 transition">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>" alt="Edit"
                                class="w-[24px] h-[24px] md:w-[28px] md:h-[28px]">
                        </button>

                        <!-- حذف -->
                        <button wire:click="confirmDelete(<?php echo e($customer->id); ?>)"
                            class="hover:scale-110 transition">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>" alt="Delete"
                                class="w-[24px] h-[24px] md:w-[28px] md:h-[28px]">
                        </button>

                        <!-- چاپ -->
                        <button wire:click="print(<?php echo e($customer->id); ?>)"
                            class="hover:scale-110 transition">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>" alt="Print"
                                class="w-[28px] h-[28px] md:w-[32px] md:h-[32px]">
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400 text-[14px] md:text-lg">
                        هیچ مشتری یافت نشد.
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>

        <!-- 📑 Pagination -->
        <div class="flex flex-col md:flex-row justify-between items-center p-3 md:p-4 border-t dark:border-gray-700 bg-white dark:bg-gray-800 gap-3 md:gap-0">
            <span class="text-[12px] md:text-sm text-gray-700 dark:text-gray-400">
                نمایش
                <span class="font-semibold"><?php echo e($customers->firstItem() ?? 0); ?></span>
                تا
                <span class="font-semibold"><?php echo e($customers->lastItem() ?? 0); ?></span>
                از
                <span class="font-semibold"><?php echo e($customers->total()); ?></span>
            </span>
            <div class="flex gap-1">
                <?php echo e($customers->links()); ?>

            </div>
        </div>
    </div>

    <?php
    $currentUser=Auth::guard('tools')->user();
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentUser && $currentUser->role==='admin' || $currentUser->role==='superadmin'): ?>
    <!-- مودال تأیید حذف مشتری -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($confirmingDelete): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-10 z-50 p-4">
        <div
            class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] rounded-[12px] shadow-xl w-full max-w-[653px] h-auto min-h-[240px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">

            <!-- دکمه بستن -->
            <button wire:click="$set('confirmingDelete', null)"
                class="absolute top-4 right-4 h-4 w-4 flex items-center justify-center">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>" alt="Close">
            </button>

            <!-- عنوان -->
            <h1 class="text-[18px] md:text-2xl text-black shabnam font-medium leading-[100%] mt-2">
                <?php echo e(__('messages.delete_customer_title')); ?>

            </h1>

            <hr class="bg-[#E1DED3] mt-6 md:mt-8">

            <!-- پیام -->
            <p class="mb-6 text-[16px] md:text-xl shabnam mt-4 md:mt-5 px-2">
                <?php echo e(__('messages.delete_customer_message')); ?>

            </p>

            <!-- دکمه‌ها -->
            <div class="flex flex-col md:flex-row justify-center gap-3 md:gap-4 mb-4 md:mb-0">
                <button wire:click="$set('confirmingDelete', null)"
                    class="px-8 md:px-20 py-2 md:py-3 bg-[#DD2424] text-white text-[16px] md:text-xl shabnam-fd rounded-xl transition">
                    <?php echo e(__('messages.no')); ?>

                </button>
                <button wire:click="deleteCustomer"
                    class="px-8 md:px-20 py-2 md:py-3 bg-gradient-to-br from-indigo-400 to-indigo-500 text-white text-[16px] md:text-xl shabnam-fd rounded-xl transition flex items-center justify-center gap-2">
                    <?php echo e(__('messages.yes')); ?>

                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/customers-table.blade.php ENDPATH**/ ?>