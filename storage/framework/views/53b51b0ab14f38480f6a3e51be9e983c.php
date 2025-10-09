<div>
    <div class="flex flex-col pr-20 mx-auto">
        <div class="flex flex-col p-4  space-y-3">
            <h1 class="text-[25px] vazir">گزارش حساب و بیلانس</h1>
            <h1 class="text-[#8C8C8C]  border-b border-[#D9D9D9] pb-6">لیست تمام مشتریان و خزانه</h1>
            <h1 class="text-[24px] font-medium">گزارش اختصاصـــــی</h1>
        </div>

        
        <div class="w-full max-w-[1465px] bg-[#F5F5F5] rounded-[12px] p-6 mx-auto"
            style="box-shadow: 0px 4px 4px 0px #00000040;">
            <form action="" class="space-y-8">

                <div class="flex flex-col md:flex-row gap-8">
                    <!-- ستون سمت راست -->
                    <div class="flex-1 flex flex-col space-y-6">

                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">نمبر حساب</label>
                            <select
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <option value="">نمبر حساب را انتخاب کنید</option>
                            </select>
                        </div>

                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">نوع سند</label>
                            <select
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <option value="">نوع سند</option>
                            </select>
                        </div>

                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">نوع معامله</label>
                            <select
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <option value="">نوع معامله</option>
                            </select>
                        </div>

                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">توضیحات</label>
                            <input type="text"
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400 placeholder:text-[#404040]"
                                placeholder="متن خود را وارد کنید">
                        </div>
                    </div>

                    <!-- ستون سمت چپ -->
                    <div class="flex-1 flex flex-col space-y-6">

                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">نوع گزارش</label>
                            <select
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <option value="">همه ترانزکشن‌ها</option>
                            </select>
                        </div>

                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">انتخاب واحد ارز برای
                                گزارش</label>
                            <select
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <option value="">نوع ارز</option>
                            </select>
                        </div>

                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">زون</label>
                                <select
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
                                <input type="text"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400 placeholder:text-[#404040]"
                                    placeholder="جستجو توسط">
                            </div>
                        </div>

                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">تاریخ شروع</label>
                                <input type="text"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400 placeholder:text-[#404040]"
                                    placeholder="1404/05/06">
                            </div>
                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">تاریخ ختم</label>
                                <input type="text"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400 placeholder:text-[#404040]"
                                    placeholder="1404/07/01">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- دکمه‌ها -->
                <div class="flex justify-center gap-4 pt-4">
                    <button type="submit"
                        class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-[16px] font-medium rounded-[12px] w-full px-8 py-4 transition">
                        بروزرسانی گزارش
                    </button>

                    <button type="button"
                        class="bg-[#B10909] hover:bg-[#8B0000] text-white text-[16px] font-medium rounded-[12px] w-full py-4 transition">
                        چاپ گزارش
                    </button>
                </div>

            </form>
        </div>


    
<div class="w-full max-w-[1465px] bg-[#F5F5F5] rounded-[12px] mt-10 p-6 mx-auto"
     style="box-shadow: 0px 4px 4px 0px #00000040;">

    <div class="relative w-[302px]">
        <input type="text"
               class="border border-[#8C8C8C] bg-transparent rounded-[12px] h-[51px] w-[302px] pl-10 pr-4"
               placeholder="جستجو کنید...">
        <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt="search"
             class="absolute left-3 top-3 w-5 h-5">
    </div>

    <table class="w-full text-sm md:text-base text-left mt-6 rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="bg-[#2B65E5] w-full text-white text-[14px] md:text-[16px] h-[50px] md:h-[67px] sticky top-0"
               style="box-shadow: 0px 4px 4px 0px #00000040;">
            <!-- سطر اول -->
            <tr class="w-full">
                <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-12 md:w-16" rowspan="2">#</th>
                <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-32 md:w-48" rowspan="2">تاریخ</th>
                <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-24 md:w-32" rowspan="2">نمبر سند</th>
                <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-32 md:w-40" rowspan="2">توضیحات</th>
                <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-24 md:w-32" rowspan="2">توسط</th>

                <th class="px-4 md:px-6 py-3 md:py-4 font-bold text-center" colspan="2">دالر</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold text-center" colspan="2">افغانی</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold text-center" colspan="2">تومان</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold text-center" colspan="2">یورو</th>
                <th class="px-2 md:px-4 py-3 md:py-4 font-bold w-36 md:w-48 text-center" rowspan="2">تسویه</th>
            </tr>
            <!-- سطر دوم -->
            <tr>
                <!-- دالر -->
                <th class="px-2 md:px-3 py-2 font-semibold text-center min-w-[70px] md:min-w-[80px]">رسید</th>
                <th class="px-2 md:px-3 py-2 font-semibold text-center min-w-[70px] md:min-w-[80px]">برد</th>

                <!-- افغانی -->
                <th class="px-2 md:px-3 py-2 font-semibold text-center min-w-[70px] md:min-w-[80px]">رسید</th>
                <th class="px-2 md:px-3 py-2 font-semibold text-center min-w-[70px] md:min-w-[80px]">برد</th>

                <!-- تومان -->
                <th class="px-2 md:px-3 py-2 font-semibold text-center min-w-[70px] md:min-w-[80px]">رسید</th>
                <th class="px-2 md:px-3 py-2 font-semibold text-center min-w-[70px] md:min-w-[80px]">برد</th>

                <!-- یورو -->
                <th class="px-2 md:px-3 py-2 font-semibold text-center min-w-[70px] md:min-w-[80px]">رسید</th>
                <th class="px-2 md:px-3 py-2 font-semibold text-center min-w-[70px] md:min-w-[80px]">برد</th>
            </tr>
        </thead>

        <tbody class="text-[14px] md:text-[15px] text-gray-800">
            <!-- سطر توضیح بیلانس‌ها -->
       

            <!-- داده‌های نمونه -->
            <tr class="border-b hover:bg-gray-50">
                <td class="px-2 md:px-4 py-3 text-center">1</td>
                <td class="px-2 md:px-4 py-3">
                    <div class="flex flex-col">
                        <span>1403/05/15</span>
                        <span>1403/05/15</span>
                    </div>
                </td>
                <td class="px-2 md:px-4 py-3">SN-001</td>
                <td class="px-2 md:px-4 py-3">خرید مواد اولیه</td>
                <td class="px-2 md:px-4 py-3">احمدی</td>

                <!-- دالر -->
                <td class="px-2 md:px-3 py-3 text-center">500</td>
                <td class="px-2 md:px-3 py-3 text-center">0</td>

                <!-- افغانی -->
                <td class="px-2 md:px-3 py-3 text-center">0</td>
                <td class="px-2 md:px-3 py-3 text-center">25,000</td>

                <!-- تومان -->
                <td class="px-2 md:px-3 py-3 text-center">-</td>
                <td class="px-2 md:px-3 py-3 text-center">1,500,000</td>

                <!-- یورو -->
                <td class="px-2 md:px-3 py-3 text-center">200</td>
                <td class="px-2 md:px-3 py-3 text-center">0</td>

                <td class="px-2 md:px-4 py-3 text-center">تأیید شده</td>
            </tr>

            <tr class="border-b hover:bg-gray-50">
                <td class="px-2 md:px-4 py-3 text-center">2</td>
               <td class="px-2 md:px-4 py-3">
                    <div class="flex flex-col">
                        <span>1403/05/15</span>
                        <span>1403/05/15</span>
                    </div>
                </td>
                <td class="px-2 md:px-4 py-3">SN-002</td>
                <td class="px-2 md:px-4 py-3">فروش محصول</td>
                <td class="px-2 md:px-4 py-3">رضایی</td>

                <!-- دالر -->
                <td class="px-2 md:px-3 py-3 text-center">0</td>
                <td class="px-2 md:px-3 py-3 text-center">150</td>

                <!-- افغانی -->
                <td class="px-2 md:px-3 py-3 text-center">45,000</td>
                <td class="px-2 md:px-3 py-3 text-center">0</td>

                <!-- تومان -->
                <td class="px-2 md:px-3 py-3 text-center">2,800,000</td>
                <td class="px-2 md:px-3 py-3 text-center">0</td>

                <!-- یورو -->
                <td class="px-2 md:px-3 py-3 text-center">0</td>
                <td class="px-2 md:px-3 py-3 text-center">75</td>

                <td class="px-2 md:px-4 py-3 text-center">در انتظار</td>
            </tr>
        </tbody>
    </table>

</div>


        
        <div class="w-full max-w-[1465px] bg-[#F5F5F5] rounded-[12px] mt-10 p-6 mx-auto">

            <div class="flex justify-between items-center text-center mx-auto">
                <h1>مجموعه کل</h1>
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


                    <tr>
                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            1
                        </td>

                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            دالر
                        </td>

                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            2500
                        </td>


                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            7830
                        </td>

                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            7200
                        </td>

                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            4930
                        </td>
                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            4930
                        </td>

                        <td class="px-2 py-4 vazir text-[18px] md:text-[16px] font-medium text-center w-16">
                            طلبکار
                        </td>



                    </tr>


                </tbody>
            </table>

        </div>


    </div>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/transactions-reports.blade.php ENDPATH**/ ?>