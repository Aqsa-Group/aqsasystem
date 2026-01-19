<div>
    <div class="container mx-auto ">
        <!-- Session Message -->
        @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2B65E5] vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    {{ session('message') }}
                </h2>
            </div>
        </div>
        @endif

        <!-- Page Header -->
        <div class="space-y-4 mb-6">
            <h1 class="text-[24px] font-medium vazir">دریافتی از صرافی ها</h1>
            <h1 class="text-[#8C8C8C]">صفحه دریافتی ها حساب مشتریان از صرافی های دیگر</h1>
        </div>

        <div class="flex-1 flex flex-col    bg-white shadow-sm backdrop-blur-2xl border border-[#D7E5EC] p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5  mx-auto"
           >


            <div class="flex items-center gap-3">


                {{-- جدول --}}
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-7 mb-4 gap-4 items-stretch">
                            <!-- دکمه ۱ - چاپ گزارش -->
                            <div>
                                <button wire:click="printReport"
                                    class="w-full flex items-center justify-center gap-2  bg-[#184D6C]  text-white px-4 py-4 rounded-xl hover:bg-blue-700 transition">
                                    <span>چاپ گزارش</span>
                                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.7714 25C10.2156 25 9.74016 24.802 9.34516 24.4062C8.95016 24.0104 8.75224 23.5358 8.75141 22.9825V20H6.49141C5.93641 20 5.46141 19.802 5.06641 19.4062C4.67141 19.0104 4.47349 18.5354 4.47266 17.9812V13.2687C4.47266 12.5604 4.71307 11.967 5.19391 11.4887C5.67474 11.0087 6.26766 10.7687 6.97266 10.7687H23.0302C23.7385 10.7687 24.3322 11.0087 24.8114 11.4887C25.2906 11.9687 25.5302 12.562 25.5302 13.2687V17.9812C25.5302 18.5362 25.3327 19.0112 24.9377 19.4062C24.5427 19.8012 24.0672 19.9991 23.5114 20H21.2514V22.9812C21.2514 23.5362 21.0535 24.0112 20.6577 24.4062C20.2618 24.8012 19.7868 24.9991 19.2327 25H10.7714ZM6.49141 18.75H8.75141C8.78391 18.2225 8.99307 17.77 9.37891 17.3925C9.76474 17.0158 10.2289 16.8275 10.7714 16.8275H19.2327C19.7743 16.8275 20.2381 17.0162 20.6239 17.3937C21.0097 17.7704 21.2189 18.2225 21.2514 18.75H23.5114C23.7356 18.75 23.9197 18.6779 24.0639 18.5337C24.2081 18.3895 24.2802 18.2054 24.2802 17.9812V13.2687C24.2802 12.9154 24.1606 12.6187 23.9214 12.3787C23.6822 12.1387 23.3852 12.0187 23.0302 12.0187H6.97266C6.61849 12.0187 6.32182 12.1387 6.08266 12.3787C5.84349 12.6187 5.72349 12.9158 5.72266 13.27V17.9812C5.72266 18.2054 5.79474 18.3895 5.93891 18.5337C6.08307 18.6779 6.26724 18.75 6.49141 18.75ZM20.0014 10.77V7.78746C20.0014 7.56246 19.9293 7.37829 19.7852 7.23496C19.641 7.09079 19.4568 7.01871 19.2327 7.01871H10.7702C10.546 7.01871 10.3618 7.09079 10.2177 7.23496C10.0735 7.37912 10.0014 7.56329 10.0014 7.78746V10.7687H8.75141V7.78746C8.75141 7.23246 8.94932 6.75704 9.34516 6.36121C9.74016 5.96537 10.2152 5.76746 10.7702 5.76746H19.2327C19.7877 5.76746 20.2627 5.96537 20.6577 6.36121C21.0535 6.75704 21.2514 7.23204 21.2514 7.78621V10.7687L20.0014 10.77ZM22.0214 15.145C22.3756 15.145 22.6722 15.025 22.9114 14.785C23.1506 14.545 23.2706 14.2483 23.2714 13.895C23.2722 13.5416 23.1522 13.2445 22.9114 13.0037C22.6706 12.7629 22.3739 12.6429 22.0214 12.6437C21.6689 12.6445 21.3718 12.7645 21.1302 13.0037C20.8885 13.2429 20.7689 13.54 20.7714 13.895C20.7739 14.25 20.8935 14.5466 21.1302 14.785C21.3668 15.0233 21.6639 15.1433 22.0214 15.145ZM20.0014 22.98V18.8462C20.0014 18.6212 19.9293 18.4366 19.7852 18.2925C19.641 18.1483 19.4568 18.0762 19.2327 18.0762H10.7702C10.546 18.0762 10.3618 18.1483 10.2177 18.2925C10.0735 18.4375 10.0014 18.622 10.0014 18.8462V22.9812C10.0014 23.2054 10.0735 23.3895 10.2177 23.5337C10.3618 23.6779 10.5464 23.75 10.7714 23.75H19.2327C19.4568 23.75 19.641 23.6779 19.7852 23.5337C19.9293 23.3895 20.0014 23.205 20.0014 22.98ZM6.49141 12.02H5.72266H24.2802H6.49141Z"
                                            fill="white" />
                                    </svg>
                                </button>
                            </div>

                            <!-- دکمه ۲ - بروزرسانی -->
                            <div>
                                <button wire:click="refreshReport"
                                    class="w-full flex items-center justify-center gap-2  bg-[#184D6C] text-white px-4 py-4 rounded-xl hover:bg-blue-700 transition">
                                    <span>بروز رسانی</span>
                                    <svg width="24" height="24" viewBox="0 0 30 30" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18.1875 27.0875C23.55 25.675 27.5 20.8 27.5 15C27.5 8.1 21.95 2.5 15 2.5C6.6625 2.5 2.5 9.45 2.5 9.45M2.5 9.45V3.75M2.5 9.45H5.0125H8.05"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M2.5 15C2.5 21.9 8.1 27.5 15 27.5" stroke="white" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="3 3" />
                                    </svg>
                                </button>
                            </div>

                            <!-- دکمه ۳ - بازنشانی فیلترها -->
                            <div>
                                <button wire:click="resetFilters"
                                    class="w-full flex items-center justify-center gap-2  bg-[#184D6C] text-white px-4 py-4 rounded-xl hover:bg-blue-700 transition">
                                    <span>بازنشانی</span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 18L18 6M6 6l12 12" stroke="white" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>

                            <!-- سلکت ۱ - انتخاب مشتری ارسال کننده -->
                            <div class="relative">
                                <select wire:model.live="fromCustomer" id="fromCustomerSelect"
                                    class="js-choices appearance-none w-full border border-[#8C8C8C] bg-white rounded-xl py-4 pl-10 pr-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm text-gray-800">
                                    <option value="">همه مشتریان ارسال کننده</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->fullname }} - {{ $customer->phone }}
                                    </option>
                                    @endforeach
                                </select>
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none z-10"
                                    width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                        stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>

                            <!-- سلکت ۲ - انتخاب مشتری دریافت کننده -->
                            <div class="relative">
                                <select wire:model.live="toCustomer" id="toCustomerSelect"
                                    class="js-choices appearance-none w-full border border-[#8C8C8C] bg-white rounded-xl py-4 pl-10 pr-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm text-gray-800">
                                    <option value="">همه مشتریان دریافت کننده</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->fullname }} - {{ $customer->phone }}
                                    </option>
                                    @endforeach
                                </select>
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none z-10"
                                    width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                        stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>

                            <!-- سلکت ۳ - انتخاب صرافی فرستنده -->
                            <div class="relative">
                                <select wire:model.live="selectedSenderSarafi" id="senderSarafiSelect"
                                    class="js-choices appearance-none w-full border border-[#8C8C8C] bg-white rounded-xl py-4 pl-10 pr-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm text-gray-800">
                                    <option value="">صرافی فرستنده</option>
                                    @foreach($sarafis as $sarafi)
                                    <option value="{{ $sarafi->id }}">{{ $sarafi->sarafi_name ?? $sarafi->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none z-10"
                                    width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                        stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>

                            <!-- سلکت ۴ - انتخاب ارز -->
                            <div class="relative">
                                <select wire:model.live="selectedCurrency" id="currencySelect"
                                    class="js-choices appearance-none w-full border border-[#8C8C8C] bg-white rounded-xl py-4 pl-10 pr-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm text-gray-800">
                                    <option value="">همه ارزها</option>
                                    @foreach($currencies as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none z-10"
                                    width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                        stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>


                        </div>
                        <table
                            class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                             <thead
                                class="sticky top-0 bg-white dark:bg-black text-black dark:text-white text-[14px] md:text-[16px] vazir">
                                <tr>
                                    <th class="px-4 py-4 font-bold w-16">#</th>
                                    <th class="px-4 py-4 font-bold w-48">حساب ارسال کننده</th>
                                    <th class="px-4 py-4 font-bold w-48">به حساب مشتری</th>
                                    <th class="px-4 py-4 font-bold w-48">نمبر احواله</th>
                                    <th class="px-4 py-4 font-bold w-48">از صرافی</th>
                                    <th class="px-4 py-4 font-bold w-40">مبلغ</th>
                                    <th class="px-4 py-4 font-bold w-32">واحد</th>
                                    <th class="px-4 py-4 font-bold w-80 text-center">توضیحات</th>
                                    <th class="px-4 py-4 font-bold w-40">تاریخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deals as $deal)
                                <tr class="text-black border-b  dark:text-white border-[#D9D9D9]
                               odd:bg-[#EFF6F9] even:bg-white dark:odd:bg-[#1E293B] dark:even:bg-black
                               transition-colors">
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        {{ $deal->fromCustomer->fullname }}
                                    </td>

                                    </td>

                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        {{ $deal->toCustomer->fullname }}
                                    </td>

                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        {{ $deal->remittance_number }}
                                    </td>



                                    <td class="px-4 py-4 vazir text-[14px] md:tex    t-[16px] font-medium w-48">
                                        {{ $deal->fromSarafiUser->sarafi_name }}

                                    </td>


                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        {{ $deal->amount }}
                                    </td>


                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        {{ $currencies[$deal->currency] ?? strtoupper($deal->currency) }}

                                    </td>


                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        {{ $deal->description }}

                                    </td>


                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium">
                                                {{ explode(' ',$deal->date)[0] }}
                                            </div>
                                            <div class="text-gray-500 text-sm mt-1">
                                                {{ \Carbon\Carbon::parse($deal->created_at)->format('h:i A') }}
                                            </div>
                                        </div>
                                    </td>





                                </tr>

                                @empty
                                <tr>
                                    <td colspan="8" class="text-center p-4 text-gray-500">
                                        دیتایی وجود ندارد
                                    </td>
                                </tr>
                                @endforelse




                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>