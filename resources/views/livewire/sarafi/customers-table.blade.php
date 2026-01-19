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

    <div class="flex flex-col  md:flex-row items-center    gap-4 mb-6 mx-auto">
        <!-- دکمه افزودن مشتری جدید -->
        <button wire:click="createCustomer"
            class="flex items-center justify-center rounded-xl w-[338px] md:w-[218px] h-[54px] bg-[#184D6C] text-white  hover:bg-blue-700">
            <img src="{{ asset('assets/sarafi/all_icon/user-add.svg') }}" alt="Add" class="w-[30px] h-[30px] me-2">
            {{ __('messages.add_customer') }}
        </button>

        <!-- 🔍 Search -->
        <div class="relative">
            <input type="text" wire:model.live="search" placeholder="جستجو..." class="w-full h-12 md:h-[51px]
                           border border-[#D7E5EC]
                           dark:bg-black dark:border-white dark:placeholder:text-white placeholder:text-black
                           rounded-[12px] pl-3 pr-12 text-sm md:text-base
                           bg-transparent relative z-0">

            {{-- آیکون --}}
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="absolute right-3 top-1/2 -translate-y-1/2 z-10 pointer-events-none dark:hidden">
                <path d="M20 20L22 22" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                <path
                    d="M6.75 3.27093C8.14732 2.46262 9.76964 2 11.5 2C16.7467 2 21 6.25329 21 11.5C21 16.7467 16.7467 21 11.5 21C6.25329 21 2 16.7467 2 11.5C2 9.76964 2.46262 8.14732 3.27093 6.75"
                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
            </svg>
        </div>

    </div>
<div
    class="relative w-full overflow-x-auto rounded-lg bg-white dark:bg-black
           border border-[#D7E5EC] dark:border-white
           shadow-sm backdrop-blur-2xl">


        <!-- 📊 Table -->
     <table
    class="min-w-[1200px] w-full text-sm text-left rtl:text-right
           text-gray-600 dark:text-gray-200">

         <thead
    class="sticky top-0 z-10 bg-white dark:bg-black
           text-[14px] md:text-[15px] font-bold vazir
           border-b border-[#D9D9D9] dark:border-gray-700">
    <tr>
        <th class="px-6 py-4">{{ __('messages.fullname') }}</th>
        <th class="px-6 py-4">{{ __('messages.account_number') }}</th>
        <th class="px-6 py-4">{{ __('messages.category') }}</th>
        <th class="px-6 py-4">{{ __('messages.city') }}</th>
        <th class="px-6 py-4">{{ __('messages.phone') }}</th>
        <th class="px-6 py-4">{{ __('messages.tazkira') }}</th>
        <th class="px-6 py-4">{{ __('messages.whatsapp') }}</th>
        <th class="px-6 py-4 text-center">{{ __('messages.actions') }}</th>
    </tr>
</thead>



         <tbody>
@forelse($customers as $customer)
<tr
    class="border-b border-[#D9D9D9] dark:border-gray-700
           odd:bg-[#EFF6F9] even:bg-white
           dark:odd:bg-[#1E293B] dark:even:bg-black
           transition-colors">

    <!-- Fullname -->
    <td class="px-6 py-4 flex items-center gap-3">
        <img
            class="w-10 h-10 rounded-full object-cover"
            src="{{ $customer->image
                ? asset('storage/'.$customer->image)
                : 'https://ui-avatars.com/api/?name='.urlencode($customer->fullname) }}"
            alt="{{ $customer->fullname }}">
        <span class="text-base font-medium text-gray-900 dark:text-white">
            {{ $customer->fullname }}
        </span>
    </td>

    <td class="px-6 py-4">{{ $customer->account_number ?? '-' }}</td>
    <td class="px-6 py-4">{{ $customer->type ?? '-' }}</td>
    <td class="px-6 py-4">{{ $customer->city }}</td>
    <td class="px-6 py-4">{{ $customer->phone }}</td>
    <td class="px-6 py-4">{{ $customer->idcard_number ?? '-' }}</td>
    <td class="px-6 py-4">{{ $customer->whatsapp_number ?? '-' }}</td>

    <!-- Actions -->
    <td class="px-6 py-4">
        <div class="flex items-center justify-center gap-2">
            <!-- Edit -->
            <button wire:click="editCustomer({{ $customer->id }})">
                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                     class="w-7 h-7 dark:hidden">
                <span class="hidden dark:block text-white">✏️</span>
            </button>

            @php $currentUser = Auth::guard('sarafi')->user(); @endphp

            @if ($currentUser && $currentUser->role === 'superadmin')
            <!-- Delete -->
            <button wire:click="confirmDelete({{ $customer->id }})">
                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                     class="w-7 h-7 dark:hidden">
                <span class="hidden dark:block text-white">🗑️</span>
            </button>
            @endif

            <!-- Print -->
            <button wire:click="print({{ $customer->id }})">
                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                     class="w-7 h-7 dark:hidden">
                <span class="hidden dark:block text-white">🖨️</span>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="px-6 py-6 text-center text-gray-500 dark:text-gray-300">
        هیچ مشتری یافت نشد.
    </td>
</tr>
@endforelse
</tbody>

        </table>

        <!-- 📑 Pagination -->
      <div
    class="flex flex-col md:flex-row md:items-center md:justify-between
           gap-3 p-4 border-t border-[#D9D9D9] dark:border-gray-700">
    <span class="text-sm">
        نمایش
        <b>{{ $customers->firstItem() ?? 0 }}</b>
        تا
        <b>{{ $customers->lastItem() ?? 0 }}</b>
        از
        <b>{{ $customers->total() }}</b>
    </span>

    <div>{{ $customers->links() }}</div>
</div>

    </div>


    @php
    $currentUser=Auth::guard('sarafi')->user();
    @endphp

    @if ($currentUser && $currentUser->role==='superadmin')
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