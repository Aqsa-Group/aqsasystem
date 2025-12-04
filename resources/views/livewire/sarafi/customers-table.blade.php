<div>
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

    <div class="flex flex-col  md:flex-row items-center md:pr-[90px]   gap-4 mb-6 mx-auto">
        <!-- دکمه افزودن مشتری جدید -->
        <button wire:click="createCustomer"
            class="flex items-center justify-center rounded-xl w-[338px] md:w-[218px] h-[54px] bg-blue-600 text-white  hover:bg-blue-700">
            <img src="{{ asset('assets/sarafi/all_icon/user-add.svg') }}" alt="Add" class="w-[30px] h-[30px] me-2">
            {{ __('messages.add_customer') }}
        </button>

        <!-- 🔍 Search -->
        <div class="relative">
           <input type="text" 
       wire:model.live="search"
       placeholder="{{ __('messages.search_customer') }}"
       class="border border-[#8C8C8C] placeholder:text-[#8C8C8C] vazir rounded-xl w-[329px] h-[54px] pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500">

            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                class="h-6 w-6 absolute left-2 bottom-4">
        </div>

    </div>


    <div class=" overflow-x-auto shadow-md sm:rounded-lg  w-[420px]   md-w-[800px] lg:w-[1268px] mx-auto bg-[#F5F5F5] dark:bg-gray-900"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">



        <!-- 📊 Table -->
        <table class="w-[1268px] text-sm  overflow-x-auto   text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
            <thead class="bg-[#2563EB] w-full  dark:bg-gray-700 text-white text-[18px] vazir mt-4">
                <tr class="mt-3">
                    <th colspan="9" class="p-3">
                        <table class="w-full">
                            <tr>
                                <th class="px-6 py-3 text-[18px] font-bold">{{ __('messages.fullname') }}</th>
                                <th class="px-6 py-3 text-[18px] font-bold">{{ __('messages.account_number') }}</th>
                                <th class="px-6 py-3 text-[18px] font-bold">{{ __('messages.category') }}</th>
                                <th class="px-6 py-3 text-[18px] font-bold">{{ __('messages.city') }}</th>
                                <th class="px-6 py-3 text-[18px] font-bold">{{ __('messages.phone') }}</th>
                                <th class="px-6 py-3 text-[18px] font-bold">{{ __('messages.tazkira') }}</th>
                                <th class="px-6 py-3 text-[18px] font-bold">{{ __('messages.whatsapp') }}</th>
                                <th class="px-6 py-3 text-[18px] font-bold text-center">{{ __('messages.actions') }}
                                </th>
                            </tr>
                        </table>
                    </th>
                </tr>
            </thead>


            <tbody>
                @forelse($customers as $customer)
                <tr class="border-b dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="flex items-center px-6 py-4 text-gray-900 dark:text-white">
                        <img class="w-10 h-10 rounded-full"
                            src="{{ $customer->image ? asset('storage/'.$customer->image) : 'https://ui-avatars.com/api/?name='.urlencode($customer->fullname) }}"
                            alt="{{ $customer->fullname }}">
                        <div class="p-3">
                            <div class="text-xl">{{ $customer->fullname }}</div>
                        </div>
                    </th>
                    <td class="px-6 py-4 text-[16px] text-black vazir">{{ $customer->account_number ?? '-' }}</td>
                    <td class="px-6 py-4 text-[16px] text-black vazir">{{ $customer->type ?? '-' }}</td>
                    <td class="px-6 py-4 text-[16px] text-black vazir">{{ $customer->city }}</td>
                    <td class="px-6 py-4 text-[16px] text-black vazir">{{ $customer->phone }}</td>
                    <td class="px-6 py-4 text-[16px] text-black vazir">{{ $customer->idcard_number ?? '-' }}</td>
                    <td class="px-6 py-4 text-[16px] text-black vazir">{{ $customer->whatsapp_number ?? '-' }}</td>
                    <td class="px-6 py-4 text-[16px] text-black vazir flex space-x-2 rtl:space-x-reverse">
                        <!-- دکمه ویرایش -->
                        <button wire:click="editCustomer({{ $customer->id }})" class="px-2 py-1">
                            <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}" alt="Edit"
                                class="w-[30px] h-[30px]">
                        </button>

                        <!-- دکمه دیلیت -->
                        <button wire:click="confirmDelete({{ $customer->id }})" class="px-2 py-1">
                            <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}" alt="Edit"
                                class="w-[30px] h-[30px]">
                        </button>

                        <!-- دکمه چاپ -->
                        <button class="px-2 py-2" wire:click="print({{ $customer->id }})">
                            <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}" alt="Edit"
                                class="w-[40px] h-[40px]">
                        </button>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        هیچ مشتری یافت نشد.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- 📑 Pagination -->
        <div class="flex justify-between items-center p-4 border-t dark:border-gray-700">
            <span class="text-sm text-gray-700 dark:text-gray-400">
                نمایش
                <span class="font-semibold">{{ $customers->firstItem() ?? 0 }}</span>
                تا
                <span class="font-semibold">{{ $customers->lastItem() ?? 0 }}</span>
                از
                <span class="font-semibold">{{ $customers->total() }}</span>
            </span>
            <div class="flex gap-1">{{ $customers->links() }}</div>
        </div>
    </div>


    @php
    $currentUser=Auth::guard('sarafi')->user();
    @endphp

    @if ($currentUser && $currentUser->role==='admin')
    <!-- مودال تأیید حذف مشتری -->
    @if ($confirmingDelete)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-10 z-50">
        <div
            class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] rounded-[12px] shadow-xl w-[653px] h-[240px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">

            <!-- دکمه بستن -->
            <button wire:click="$set('confirmingDelete', null)"
                class="absolute top-4 right-4 h-4 w-4 flex items-center justify-center">
                <img src="{{ asset('assets/sarafi/all_icon/close.svg') }}" alt="Close">
            </button>

            <!-- عنوان -->
            <h1 class="text-2xl text-black shabnam font-medium leading-[100%]">
                {{ __('messages.delete_customer_title') }}
            </h1>

            <hr class="bg-[#E1DED3] mt-8">

            <!-- پیام -->
            <p class="mb-6 text-xl shabnam mt-5">
                {{ __('messages.delete_customer_message') }}
            </p>

            <!-- دکمه‌ها -->
            <div class="flex justify-center gap-4">
                <button wire:click="$set('confirmingDelete', null)"
                    class="px-20 py-3 bg-[#DD2424] text-white text-xl shabnam-fd rounded-xl transition">
                    {{ __('messages.no') }}
                </button>
                <button wire:click="deleteCustomer"
                    class="px-20 py-3 bg-[#2563EB] text-white text-xl shabnam-fd rounded-xl transition flex items-center gap-2">
                    {{ __('messages.yes') }}
                </button>
            </div>
        </div>
    </div>
    @endif
    @endif



</div>