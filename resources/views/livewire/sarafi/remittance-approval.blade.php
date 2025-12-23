<div>
    <div class="container mx-auto px-4">
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
            <h1 class="text-[24px] font-medium vazir">مدیریت تایید حواله‌ها</h1>
            <h1 class="text-[#8C8C8C]">صفحه تایید و رد حواله‌های بانکی</h1>
        </div>

        <hr class="my-6 border-t border-[#D9D9D9] w-full">

        <!-- جدول تایید حواله‌ها -->
        <div class="w-full">
            <div class="bg-[#F5F5F5] dark:bg-black dark:border dark:border-white dark:text-white p-6 rounded-[12px]"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <div
                    class="flex flex-col md:flex-row justify-between items-center border border-[#8C8C8C] p-4 rounded-[12px] mb-4 gap-3">
                    <h1 class="text-xl md:text-2xl vazir dark:text-white">حواله‌های در انتظار تایید</h1>
                    <div class="text-gray-600 vazir dark:text-white">
                        تعداد: {{ count($pendingApprovals) }} حواله
                    </div>
                </div>

                <div class="overflow-x-auto w-full">
                    <div class="max-h-[600px] overflow-y-auto">
                        <table
                            class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="bg-[#2B65E5] dark:bg-[#2B65E5] text-white text-[16px] vazir h-16 sticky top-0">
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
                                @forelse($pendingApprovals as $key => $approval)
                                <tr
                                    class="text-black border-b dark:text-white border-[#D9D9D9] bg-transparent hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-4 vazir text-[16px] font-medium text-center">
                                        {{ $key + 1 }}
                                    </td>
                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <div class="space-y-1">
                                            <p class="font-semibold">{{ $approval->customer->fullname ?? '-' }}</p>
                                            <p class="text-sm text-gray-500 dark:text-white">{{
                                                $approval->source_account }}</p>
                                            <p class="text-xs text-gray-400 dark:text-white">{{ $approval->from_bank }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <div class="space-y-1">
                                            <p class="font-semibold">{{ $approval->recipient->fullname ??
                                                $approval->giver_name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-white">{{ $approval->to_bank }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        {{ number_format($approval->amount) }}
                                    </td>
                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        @php
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
                                        $currencyName = collect($currencies)->firstWhere('code',
                                        $approval->currency)['name_fa'] ?? $approval->currency;
                                        @endphp
                                        {{ $currencyName }}
                                    </td>
                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <code
                                            class="bg-gray-100 dark:bg-slate-600 px-2 py-1 rounded">{{ $approval->tracking_code }}</code>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[16px] font-medium">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium">{{ $approval->created_at->format('Y/m/d') }}</div>
                                            <div class="text-gray-500 text-sm">{{ $approval->created_at->format('H:i:s')
                                                }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button wire:click="confirmApprove({{ $approval->id }})"
                                                class="bg-blue-600 hover:bg-blue-500 text-white px-10 py-2 rounded-lg text-sm vazir transition-colors flex items-center gap-1">

                                                تایید
                                            </button>

                                            <button wire:click="confirmReject({{ $approval->id }})"
                                                class="bg-red-600 hover:bg-red-600 text-white px-10 py-2 rounded-lg text-sm vazir transition-colors flex items-center gap-1">
                                                رد
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-gray-500 py-8 text-lg">
                                        هیچ حواله‌ای در انتظار تایید وجود ندارد
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

    <!-- Modal تایید -->
    @if ($confirmApproveId)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-[#F5F5F5] dark:bg-black dark:border dark:border-white dark:text-white p-6 rounded-[12px] shadow-xl w-[600px] max-h-[90vh] overflow-y-auto"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
            <h2 class="text-xl vazir font-bold mb-4 text-center">تایید حواله</h2>
            <p class="vazir mb-4 text-center">آیا از تایید این حواله اطمینان دارید؟</p>

            <textarea wire:model="approvalNotes" placeholder="یادداشت تایید (اختیاری)"
                class="w-full p-3 border border-gray-300 rounded-lg mb-4 vazir" rows="3"></textarea>

            <!-- بخش کمیشن -->
            <div
                class="mb-4 p-4 border border-gray-200 rounded-lg dark:bg-black dark:border-white dark:border  bg-gray-50">
                <div class="flex items-center justify-between mb-3">
                    <label class="flex items-center space-x-2 space-x-reverse cursor-pointer">
                        <input type="checkbox" wire:model.live="withCommission" class="rounded border-gray-300 w-5 h-5">
                        <span class="vazir font-medium">ثبت کمیشن</span>
                    </label>
                </div>


                @if($withCommission)
                <div class="space-y-3 animate-fadeIn">
                    <!-- حساب کمیشن -->
                    <div>
                        <label class="block text-sm font-medium text-black mb-1 vazir">حساب کمیشن</label>
                        <select wire:model="commissionAccount"
                            class="w-full h-[50px] p-3 rounded-[8px] border border-[#8C8C8C] dark:bg-black dark:border dark:border-white bg-white focus:ring-2 focus:ring-blue-500 vazir">
                            <option value="">انتخاب حساب کمیشن</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer['id'] }}">
                                {{ $customer['account_number'] }} - {{ $customer['fullname'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('commissionAccount')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- ارز کمیشن -->
                    <div>
                        <label class="block text-sm font-medium text-black mb-1 vazir">ارز کمیشن</label>
                        <select wire:model="commissionCurrency"
                            class="w-full h-[50px] p-3 rounded-[8px] border dark:bg-black dark:border dark:border-white  border-[#8C8C8C] bg-white focus:ring-2 focus:ring-blue-500 vazir">
                            <option value="">انتخاب ارز</option>
                            <option value="usd">دالر</option>
                            <option value="afn">افغانی</option>
                            <option value="eur">یورو</option>
                            <option value="irr">تومان</option>
                            <option value="aed">درهم</option>
                            <option value="try">لیره</option>
                            <option value="cny">یوان</option>
                            <option value="pkr">کلدار</option>
                        </select>
                        @error('commissionCurrency')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- مبلغ کمیشن -->
                    <div>
                        <label class="block text-sm font-medium text-black mb-1 vazir">مبلغ کمیشن</label>
                        <input type="text" wire:model="commissionAmount" placeholder="0"
                            class="w-full h-[50px] p-3 text-left rounded-[8px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 bg-white vazir" />
                        @error('commissionAmount')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror

                    </div>
                </div>
                @endif
            </div>

            <div class="flex justify-center  text-center gap-3">
                <button wire:click="cancelAction"
                    class="w-full text-center py-3 bg-red-500 text-white rounded-lg vazir hover:bg-red-600 transition">
                    انصراف
                </button>
                <button wire:click="approveRemittance" wire:loading.attr='disabled' wire:target='approveRemittance'
                    class="w-full  py-3 bg-blue-500 text-white rounded-lg vazir hover:bg-blue-700 transition flex text-center justify-center gap-2">
                    <span wire:loading.remove wire:target='approveRemittance'>
                        تایید {{ $withCommission ? 'با کمیشن' : '' }}
                    </span>

                    <span wire:loading wire:target="approveRemittance" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        در حال تایید
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif
    <!-- Modal رد -->
    @if ($confirmRejectId)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div
            class="bg-white dark:bg-black dark:text-white dark:border dark:border-white p-6 rounded-[12px] shadow-xl w-[600px]">
            <h2 class="text-xl vazir font-bold mb-4 text-center">رد حواله</h2>
            <p class="vazir mb-4 text-center">آیا از رد این حواله اطمینان دارید؟</p>

            <textarea wire:model="approvalNotes" placeholder="دلیل رد (اختیاری)"
                class="w-full p-3 border border-gray-300 rounded-lg mb-4 vazir" rows="3"></textarea>

            <div class="flex justify-center gap-3">
                <button wire:click="cancelAction" class="w-full py-2 bg-gray-500 text-white rounded-lg vazir">
                    در انتظار بماند
                </button>
                <button wire:click="rejectRemittance" class="w-full py-2 bg-red-500 text-white rounded-lg vazir">
                    بله رد شود
                </button>
            </div>
        </div>
    </div>
    @endif
</div>