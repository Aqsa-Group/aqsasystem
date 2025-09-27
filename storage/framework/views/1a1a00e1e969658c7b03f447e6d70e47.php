    <div>
        <!-- دکمه افزودن مشتری جدید -->
        <div class="mb-4">
            <button 
                wire:click="createCustomer"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
                ➕ افزودن مشتری جدید
            </button>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <!-- 🔍 Search -->
            <div class="flex flex-col md:flex-row items-center justify-between p-4 bg-white dark:bg-gray-900 gap-4">
                <div class="relative w-full md:w-1/3">
                    <input type="text" wire:model.live="search"
                        class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg 
                            bg-gray-50 focus:ring-blue-500 focus:border-blue-500 
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
                            dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="جستجو در مشتریان">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- 📊 Table -->
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="p-4"><input type="checkbox" class="w-4 h-4 text-blue-600 rounded-sm"></th>
                        <th class="px-6 py-3">نام</th>
                        <th class="px-6 py-3">شماره حساب</th>
                        <th class="px-6 py-3">دسته</th>
                        <th class="px-6 py-3">شهر</th>
                        <th class="px-6 py-3">شماره تلفن</th>
                        <th class="px-6 py-3">نمبر تذکره</th>
                        <th class="px-6 py-3">واتساپ</th>
                        <th class="px-6 py-3 text-center">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="bg-white border-b dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="w-4 p-4">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded-sm">
                        </td>
                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 dark:text-white">
                            <img class="w-10 h-10 rounded-full"
                                src="<?php echo e($customer->image ? asset('storage/'.$customer->image) : 'https://ui-avatars.com/api/?name='.urlencode($customer->fullname)); ?>"
                                alt="<?php echo e($customer->fullname); ?>">
                            <div class="ps-3">
                                <div class="text-base font-semibold"><?php echo e($customer->fullname); ?></div>
                            </div>
                        </th>
                        <td class="px-6 py-4"><?php echo e($customer->account_number ?? '-'); ?></td>
                        <td class="px-6 py-4"><?php echo e($customer->type ?? '-'); ?></td>
                        <td class="px-6 py-4"><?php echo e($customer->city); ?></td>
                        <td class="px-6 py-4"><?php echo e($customer->phone); ?></td>
                        <td class="px-6 py-4"><?php echo e($customer->idcard_number ?? '-'); ?></td>
                        <td class="px-6 py-4"><?php echo e($customer->whatsapp_number ?? '-'); ?></td>
                        <td class="px-6 py-4 flex space-x-2 rtl:space-x-reverse">
                            <!-- ✏️ Edit -->
                            <button wire:click="editCustomer(<?php echo e($customer->id); ?>)"
                                    class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                ✏️
                            </button>

                            <!-- 🗑️ Delete -->
                            <button wire:click="confirmDelete(<?php echo e($customer->id); ?>)"
                                    class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                🗑️
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            هیچ مشتری یافت نشد.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- 📑 Pagination -->
            <div class="flex justify-between items-center p-4 bg-white dark:bg-gray-900 border-t dark:border-gray-700">
                <span class="text-sm text-gray-700 dark:text-gray-400">
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

        <!-- ✅ Success message -->
        <?php if(session()->has('message')): ?>
            <div class="mt-4 p-4 text-green-600 bg-green-100 rounded-lg text-center font-semibold">
                <?php echo e(session('message')); ?>

            </div>
        <?php endif; ?>

        <!-- ❗ Delete Confirmation Modal -->
        <?php if($confirmingDelete): ?>
        <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-96">
                <h2 class="text-xl font-bold text-red-600 mb-4">⚠️ حذف مشتری</h2>
                <p class="text-gray-700 dark:text-gray-200">آیا مطمئن هستید که می‌خواهید این مشتری را حذف کنید؟</p>

                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="$set('confirmingDelete', null)"
                            class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                        لغو
                    </button>
                    <button wire:click="deleteCustomer"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        حذف
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/customers-table.blade.php ENDPATH**/ ?>