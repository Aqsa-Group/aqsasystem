<div>
    <div class="space-y-4 mb-6">
        <h1 class="text-[24px] font-medium vazir">درج ارز برای بیلانس</h1>
        <h1 class="text-[#8C8C8C]">اضافه ویرایش ارزها برای بیلانس گیری</h1>
    </div>
    <hr class="my-6 border-t border-[#D9D9D9] w-full">


    <div class="flex flex-col bg-[#F5F5F5] w-full mt-1 lg:w-[574px] p-[12px] h-[760px] rounded-[12px] space-y-2"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <div class="flex  gap-2 border border-[#8C8C8C] rounded-[12px] p-6 ">
            <img src="<?php echo e(asset('assets/sarafi/all_icon/exchange-rate.svg')); ?>" alt="">
            <p>ثبت قیمت ارز</p>
        </div>

        <form action="">
            <div class="flex  gap-2 mt-3 ">
                <div class="lg:w-[290px]">
                    <label class="block text-[16px] font-medium text-black mb-1 vazir">واحد ارز اصلی</label>
                    <input type="text" id="datePicker" wire:model="source_currency" placeholder="دالر" value="دالر"
                        class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                </div>
                <div class="lg:w-[290px]">
                    <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                    <input type="text" id="datePicker" wire:model="date" wire:ignore placeholder="YYYY/MM/DD"
                        class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                </div>
            </div>

            <table class="w-full text-center border-collapse mt-4">
                <thead class>
                    <tr class="bg-[#2B65E5] text-white">
                        <th class="px-6 py-3">واحد ارز</th>
                        <th class="px-4 py-3">قیمت خرید</th>
                        <th class="px-4 py-3">قیمت فروش</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $currencies = ['افغانی', 'تومان', 'یورو', 'کلدار', 'درهم', 'لیره', 'یوان چین', 'لیره'];
                    ?>

                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-b">
                        
                        <td class="px-4 py-2 font-bold text-gray-700">
                            <?php echo e($currency); ?>

                        </td>

                        
                        <td class="px-4 py-2">
                            <input type="text" step="0.01" wire:model.defer="exchangeRates.<?php echo e($currency); ?>.buy"
                                class="w-full outline-none bg-transparent rounded px-2 py-1 text-right"
                                placeholder="0.00">
                        </td>

                        
                        <td class="px-4 py-2">
                            <input type="text" step="0.01" wire:model.defer="exchangeRates.<?php echo e($currency); ?>.sell"
                                class="w-full outline-none bg-transparent rounded px-2 py-1 text-right"
                                placeholder="0.00">
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
            <div class="flex gap-4 p-4 justify-center items-center text-center flex-wrap">



                <button type="button" wire:click="submitAndPrint"
                    class="bg-[#2563EB] text-[16px] vazir font-semibold rounded-[8px] px-24 py-3 text-white">
                    ثبت و چاپ
                </button>


                <button type="button" wire:click="cancel"
                    class="bg-[#DD2424] text-[16px] vazir font-semibold rounded-[8px] px-24 py-3 text-white">
                    انصراف
                </button>
            </div>
        </form>

    </div>

    


</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/exchange-rate.blade.php ENDPATH**/ ?>