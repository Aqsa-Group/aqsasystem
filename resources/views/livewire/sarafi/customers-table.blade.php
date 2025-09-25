<div>
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
                @forelse($customers as $customer)
                <tr class="bg-white border-b dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="w-4 p-4">
                        <input type="checkbox" class="w-4 h-4 text-blue-600 rounded-sm">
                    </td>
                    <th scope="row" class="flex items-center px-6 py-4 text-gray-900 dark:text-white">
                        <img class="w-10 h-10 rounded-full"
                             src="{{ $customer->image ? asset('storage/'.$customer->image) : 'https://ui-avatars.com/api/?name='.urlencode($customer->fullname) }}"
                             alt="{{ $customer->fullname }}">
                        <div class="ps-3">
                            <div class="text-base font-semibold">{{ $customer->fullname }}</div>
                        </div>
                    </th>
                    <td class="px-6 py-4">{{ $customer->account_number ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $customer->type ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $customer->city }}</td>
                    <td class="px-6 py-4">{{ $customer->phone }}</td>
                    <td class="px-6 py-4">{{ $customer->idcard_number ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $customer->whatsapp_number ?? '-' }}</td>
                    <td class="px-6 py-4 flex space-x-2 rtl:space-x-reverse">
                        <!-- ✏️ Edit -->
                        <!-- ✏️ Edit -->
        <button 
            onclick="Livewire.emitTo('sarafi.customers', 'edit-customer', {{ $customer->id }})"
            class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
            ✏️
        </button>




                        <!-- 🗑️ Delete -->
                        <button wire:click="confirmDelete({{ $customer->id }})"
                                class="text-red-500 hover:text-red-700">🗑️</button>
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
        <div class="flex justify-between items-center p-4 bg-white dark:bg-gray-900 border-t dark:border-gray-700">
            <span class="text-sm text-gray-700 dark:text-gray-400">
                نمایش 
                <span class="font-semibold">{{ $customers->firstItem() }}</span> 
                تا 
                <span class="font-semibold">{{ $customers->lastItem() }}</span> 
                از 
                <span class="font-semibold">{{ $customers->total() }}</span>
            </span>
            <div class="flex gap-1">
                {{ $customers->links() }}
            </div>
        </div>
    </div>

    <!-- ✅ Success message -->
    @if (session()->has('message'))
        <div class="mt-4 text-green-600 text-center font-semibold">
            {{ session('message') }}
        </div>
    @endif

    <!-- ❗ Delete Confirmation Modal -->
    @if($confirmingDelete)
    <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-96">
            <h2 class="text-xl font-bold text-red-600 mb-4">⚠️ حذف مشتری</h2>
            <p class="text-gray-700 dark:text-gray-200">آیا مطمئن هستید که می‌خواهید این مشتری را حذف کنید؟</p>

            <div class="mt-6 flex justify-end gap-3">
                <button wire:click="$set('confirmingDelete', false)"
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
    @endif
</div>
