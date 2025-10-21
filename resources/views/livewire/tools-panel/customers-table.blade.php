<div>
    @if (session()->has('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-gradient-to-br from-indigo-400 to-indigo-500 vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                {{ session('message') }}
            </h2>
        </div>
    </div>
    @endif

    <div class="flex flex-col md:flex-row items-center  gap-4 mb-6 mr-14">
        <!-- دکمه افزودن مشتری جدید -->
        <button wire:click="createCustomer"
            class="flex items-center justify-center rounded-xl w-[218px] h-[54px] bg-gradient-to-br from-indigo-400 to-indigo-500 text-white  hover:bg-gradient-to-br from-indigo-400 to-indigo-500">
            <img src="{{ asset('assets/sarafi/all_icon/user-add.svg') }}" alt="Add" class="w-[30px] h-[30px] me-2">
            {{ __('messages.add_customer') }}
        </button>

        <!-- 🔍 Search -->
        <div class="relative">
            <input type="text" placeholder="{{ __('messages.search_customer') }}"
                class="border border-[#8C8C8C] placeholder:text-[#8C8C8C] vazir rounded-xl w-[329px] h-[54px] pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500">
            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                class="h-6 w-6 absolute left-2 bottom-4">
        </div>

    </div>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg w-[1500px] mr-10 bg-[#F5F5F5] dark:bg-gray-900"
    style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

    <table class="min-w-full text-sm text-gray-700 dark:text-gray-400 text-center border-collapse">
        <!-- 🧭 Table Header -->
        <thead class="bg-gradient-to-br from-indigo-400 to-indigo-500 dark:bg-gray-700 text-white text-[18px]  vazir h-14">
            <tr>
                <th class="px-6 py-3 font-semibold text-right">نام کامل</th>
                <th class="px-6 py-3 font-semibold">شماره حساب</th>
                <th class="px-6 py-3 font-semibold">شهر</th>
                <th class="px-6 py-3 font-semibold">شماره تماس</th>
                <th class="px-6 py-3 font-semibold">تذکره</th>
                <th class="px-6 py-3 font-semibold text-center">عملیات</th>
            </tr>
        </thead>

        <!-- 📊 Table Body -->
        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700 ">
            @forelse($customers as $customer)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <!-- نام -->
                <td class="px-6 py-4 text-right flex items-center gap-3">
                    <img class="w-10 h-10 rounded-full"
                        src="{{ $customer->image ? asset('storage/'.$customer->image) : 'https://ui-avatars.com/api/?name='.urlencode($customer->fullname) }}"
                        alt="{{ $customer->fullname }}">
                    <span class="text-lg text-gray-900 dark:text-white">{{ $customer->fullname }}</span>
                </td>

                <!-- شماره حساب -->
                <td class="px-6 py-4 text-[16px] text-black vazir">{{ $customer->account_number ?? '-' }}</td>

                <!-- شهر -->
                <td class="px-6 py-4 text-[16px] text-black vazir">{{ $customer->city ?? '-' }}</td>

                <!-- تلفن -->
                <td class="px-6 py-4 text-[16px] text-black vazir">{{ $customer->phone ?? '-' }}</td>

                <!-- تذکره -->
                <td class="px-6 py-4 text-[16px] text-black vazir">{{ $customer->idcard_number ?? '-' }}</td>

                <!-- عملیات -->
                <td class="px-6 py-4 flex justify-center items-center gap-3">
                    <!-- ویرایش -->
                    <button wire:click="editCustomer({{ $customer->id }})"
                        class="hover:scale-110 transition">
                        <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}" alt="Edit"
                            class="w-[28px] h-[28px]">
                    </button>

                    <!-- حذف -->
                    <button wire:click="confirmDelete({{ $customer->id }})"
                        class="hover:scale-110 transition">
                        <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}" alt="Delete"
                            class="w-[28px] h-[28px]">
                    </button>

                    <!-- چاپ -->
                    <button wire:click="print({{ $customer->id }})"
                        class="hover:scale-110 transition">
                        <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}" alt="Print"
                            class="w-[32px] h-[32px]">
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400 text-lg">
                    هیچ مشتری یافت نشد.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 📑 Pagination -->
    <div class="flex justify-between items-center p-4 border-t dark:border-gray-700 bg-white dark:bg-gray-800">
        <span class="text-sm text-gray-700 dark:text-gray-400">
            نمایش
            <span class="font-semibold">{{ $customers->firstItem() ?? 0 }}</span>
            تا
            <span class="font-semibold">{{ $customers->lastItem() ?? 0 }}</span>
            از
            <span class="font-semibold">{{ $customers->total() }}</span>
        </span>
        <div class="flex gap-1">
            {{ $customers->links() }}
        </div>
    </div>
</div>

    @php
    $currentUser=Auth::guard('tools')->user();
    @endphp

    @if ($currentUser && $currentUser->role==='admin' || $currentUser->role==='superadmin')
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
                    class="px-20 py-3 bg-gradient-to-br from-indigo-400 to-indigo-500 text-white text-xl shabnam-fd rounded-xl transition flex items-center gap-2">
                    {{ __('messages.yes') }}
                </button>
            </div>
        </div>
    </div>
    @endif
    @endif



</div>