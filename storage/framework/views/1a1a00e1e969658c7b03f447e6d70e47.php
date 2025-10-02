<div>
    <div class="flex flex-col md:flex-row items-center  gap-4 mb-6 mr-14">
        <!-- دکمه افزودن مشتری جدید -->
        <button wire:click="createCustomer"
            class="flex items-center justify-center rounded-xl w-[218px] h-[54px] bg-blue-600 text-white  hover:bg-blue-700">
            <img src="<?php echo e(asset('assets/sarafi/all_icon/user-add.svg')); ?>" alt="Add" class="w-[30px] h-[30px] me-2">
             <?php echo e(__('messages.add_customer')); ?>

        </button>

        <!-- 🔍 Search -->
          <div class="relative">
                    <input type="text" placeholder="<?php echo e(__('messages.search_customer')); ?>"
                        class="border border-[#8C8C8C] placeholder:text-[#8C8C8C] vazir rounded-xl w-[329px] h-[54px] pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                        class="h-6 w-6 absolute left-2 bottom-4">
                </div>

    </div>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-[1500px] mr-10 bg-[#F5F5F5] dark:bg-gray-900"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">



        <!-- 📊 Table -->
        <table class="w-[1468px] text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mx-auto">
            <thead class="text-[18px] vazir h-20  text-white  bg-[#2563EB] dark:bg-gray-700 dark:text-gray-400">
                <tr class="pt-5">
                    <th class="p-4"><input type="checkbox" class="w-4 h-4 text-blue-600 rounded-sm"></th>
                    <th class="px-6 py-3 text-[18px] font-bold"><?php echo e(__('messages.fullname')); ?></th>
                    <th class="px-6 py-3 text-[18px] font-bold"><?php echo e(__('messages.account_number')); ?></th>
                    <th class="px-6 py-3 text-[18px] font-bold"><?php echo e(__('messages.category')); ?></th>
                    <th class="px-6 py-3 text-[18px] font-bold"><?php echo e(__('messages.city')); ?></th>
                    <th class="px-6 py-3 text-[18px] font-bold"><?php echo e(__('messages.phone')); ?></th>
                    <th class="px-6 py-3 text-[18px] font-bold"><?php echo e(__('messages.tazkira')); ?></th>
                    <th class="px-6 py-3 text-[18px] font-bold"><?php echo e(__('messages.whatsapp')); ?></th>
                    <th class="px-6 py-3 text-[18px] font-bold text-center"><?php echo e(__('messages.actions')); ?></th>
                </tr>
            </thead>
            <tbody>
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="w-4 p-4"><input type="checkbox" class="w-4 h-4 text-blue-600 rounded-sm"></td>
                    <th scope="row" class="flex items-center px-6 py-4 text-gray-900 dark:text-white">
                        <img class="w-10 h-10 rounded-full"
                            src="<?php echo e($customer->image ? asset('storage/'.$customer->image) : 'https://ui-avatars.com/api/?name='.urlencode($customer->fullname)); ?>"
                            alt="<?php echo e($customer->fullname); ?>">
                        <div class="p-3">
                            <div class="text-xl"><?php echo e($customer->fullname); ?></div>
                        </div>
                    </th>
                    <td class="px-6 py-4 text-[16px] text-black vazir"><?php echo e($customer->account_number ?? '-'); ?></td>
                    <td class="px-6 py-4 text-[16px] text-black vazir"><?php echo e($customer->type ?? '-'); ?></td>
                    <td class="px-6 py-4 text-[16px] text-black vazir"><?php echo e($customer->city); ?></td>
                    <td class="px-6 py-4 text-[16px] text-black vazir"><?php echo e($customer->phone); ?></td>
                    <td class="px-6 py-4 text-[16px] text-black vazir"><?php echo e($customer->idcard_number ?? '-'); ?></td>
                    <td class="px-6 py-4 text-[16px] text-black vazir"><?php echo e($customer->whatsapp_number ?? '-'); ?></td>
                    <td class="px-6 py-4 text-[16px] text-black vazir flex space-x-2 rtl:space-x-reverse">
                        <!-- دکمه ویرایش -->
                        <button wire:click="editCustomer(<?php echo e($customer->id); ?>)" class="px-2 py-1">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>" alt="Edit"
                                class="w-[30px] h-[30px]">
                        </button>

                        <!-- دکمه دیلیت -->
                        <button wire:click="confirmDelete(<?php echo e($customer->id); ?>)" class="px-2 py-1">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>" alt="Edit"
                                class="w-[30px] h-[30px]">
                        </button>

                        <!-- دکمه چاپ -->
                        <button class="px-2 py-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>" alt="Edit"
                                class="w-[40px] h-[40px]">
                        </button>
                    </td>

                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        هیچ مشتری یافت نشد.
                    </td>
                </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>

        <!-- 📑 Pagination -->
        <div class="flex justify-between items-center p-4 border-t dark:border-gray-700">
            <span class="text-sm text-gray-700 dark:text-gray-400">
                نمایش
                <span class="font-semibold"><?php echo e($customers->firstItem() ?? 0); ?></span>
                تا
                <span class="font-semibold"><?php echo e($customers->lastItem() ?? 0); ?></span>
                از
                <span class="font-semibold"><?php echo e($customers->total()); ?></span>
            </span>
            <div class="flex gap-1"><?php echo e($customers->links()); ?></div>
        </div>
    </div>

    <!-- ✅ Success message -->
  <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
<div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
    <div class="bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-100 rounded-lg shadow-xl p-6 w-80 text-center">
        <p class="font-semibold">
            <?php echo e(session('message')); ?>

        </p>
        <button onclick="this.parentElement.parentElement.remove()"
            class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            ✖ بستن
        </button>
    </div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->


    <!-- ❗ Delete Confirmation Modal -->
   <!--[if BLOCK]><![endif]--><?php if($confirmingDelete): ?>
<div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-96">
        <h2 class="text-xl font-bold text-red-600 mb-4">
            <?php echo e(__('messages.delete_customer_title')); ?>

        </h2>
        <p class="text-gray-700 dark:text-gray-200">
            <?php echo e(__('messages.delete_customer_message')); ?>

        </p>

        <div class="mt-6 flex justify-end gap-3">
            <button wire:click="$set('confirmingDelete', null)"
                class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                <?php echo e(__('messages.cancel')); ?>

            </button>
            <button wire:click="deleteCustomer"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                <?php echo e(__('messages.delete')); ?>

            </button>
        </div>
    </div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/customers-table.blade.php ENDPATH**/ ?>