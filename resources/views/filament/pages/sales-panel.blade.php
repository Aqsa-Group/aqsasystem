<x-filament-panels::page>

    <div x-data="{ saleType: @entangle('saleType') }" class="space-y-6 p-4">

        {{-- ردیف دکمه‌ها و انتخاب مشتری --}}
        <div class="flex items-center justify-between mb-3 gap-6">

            {{-- دکمه‌ها --}}
            <div class="flex gap-3 flex-1 max-w-xs">
                <x-filament::button
                    @click="saleType = 'retail'; $wire.switchToRetail();"
                    color="danger"
                    icon="heroicon-o-shopping-bag"
                    class="flex-1 py-3 text-base font-bold rounded-xl shadow hover:scale-105 transition"
                    x-bind:class="saleType === 'retail' ? 'ring-2 ring-red-400' : ''">
                    فروش پرچون
                </x-filament::button>

                <x-filament::button
                    @click="saleType = 'wholesale'; $wire.switchToWholesale();"
                    color="success"
                    icon="heroicon-o-shopping-cart"
                    class="flex-1 py-3 text-base font-bold rounded-xl shadow hover:scale-105 transition"
                    x-bind:class="saleType === 'wholesale' ? 'ring-2 ring-green-400' : ''">
                    فروش عمده
                </x-filament::button>
            </div>

            {{-- انتخاب مشتری فقط در فروش عمده --}}
            <template x-if="saleType === 'wholesale'">
                <div class="flex-shrink-0 w-48">
                    <label class="block text-gray-700 dark:text-gray-200 text-sm mb-1">👤 انتخاب خریدار</label>
                    <select wire:model.defer="customer_id"
                        class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-400">
                        <option value="">انتخاب خریدار...</option>
                        @foreach (\App\Models\Import\Customer::where('user_id', auth()->id())->get() as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </template>
            

        </div>

        {{-- Layout دو ستونه --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- ستون فرم افزودن محصول --}}
            <div class="col-span-1 space-y-4">
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700 space-y-3">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-1">
                        🏷️ افزودن محصول
                    </h2>

                    <form wire:submit.prevent="submitForm" class="space-y-3 relative">

                        {{-- بارکد --}}
                        <div>
                            <label class="block text-gray-700 dark:text-gray-200 text-sm mb-1">اسکن بارکد</label>
                            <input wire:model="barcode" placeholder="اسکن بارکد..."
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50 dark:bg-gray-800 dark:text-gray-100" />
                        </div>

                        {{-- جستجوی نام محصول --}}
                        <div class="relative">
                            <label class="block text-gray-700 dark:text-gray-200 text-sm mb-1">جستجو با نام محصول</label>
                            <input wire:model.debounce.200ms="searchName" placeholder="نام محصول..."
                                autocomplete="off"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50 dark:bg-gray-800 dark:text-gray-100" />
                            
                            @if (!empty($suggestions))
                                <div class="absolute w-full bg-white dark:bg-gray-800 border rounded-lg shadow mt-1 max-h-40 overflow-y-auto z-50">
                                    @foreach ($suggestions as $product)
                                        <div class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer text-gray-800 dark:text-gray-200"
                                             wire:click="selectProduct({{ $product['id'] }})">
                                            {{ $product['name'] }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- تعداد --}}
                        <div>
                            <label class="block text-gray-700 dark:text-gray-200 text-sm mb-1">تعداد</label>
                            <input type="number" wire:model="quantity" min="1"
                                class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 dark:bg-gray-800 dark:text-gray-100" />
                        </div>

                        {{-- دکمه افزودن --}}
                        <x-filament::button type="submit" color="success" class="w-full py-2 text-sm rounded-lg">
                            افزودن به فاکتور
                        </x-filament::button>
                    </form>
                </div>
            </div>

            {{-- ستون جدول کالاها و خلاصه فاکتور --}}
            <div class="col-span-2 space-y-4">

                {{-- جدول کالاها --}}
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700 overflow-x-auto">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-lg font-bold text-gray-800 dark:text-gray-200">🛒 لیست کالاهای فاکتور</span>
                        <span class="text-sm text-gray-500">تعداد کالا: {{ count($items) }}</span>
                    </div>
                    <table class="w-full text-sm text-right">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="p-2">نام محصول</th>
                                <th class="p-2">تعداد</th>
                                <th class="p-2">قیمت واحد (افغانی)</th>
                                <th class="p-2">مجموع (افغانی)</th>
                                <th class="p-2">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($items as $index => $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="p-2 text-gray-800 dark:text-gray-200">{{ $item['name'] }}</td>
                                    <td class="p-2 flex items-center justify-center gap-2">
                                        <button wire:click="decreaseQuantity({{ $index }})"
                                            class="px-2 bg-red-500 text-white rounded">−</button>
                                        <span>{{ $item['quantity'] }}</span>
                                        <button wire:click="increaseQuantity({{ $index }})"
                                            class="px-2 bg-green-500 text-white rounded">+</button>
                                    </td>
                                    <td class="p-2 text-gray-600 dark:text-gray-300">{{ number_format($item['price']) }}</td>
                                    <td class="p-2 font-semibold text-blue-600 dark:text-blue-400">{{ number_format($item['total']) }}</td>
                                    <td class="p-2 text-center">
                                        <button wire:click="removeItem({{ $index }})"
                                            class="px-3 py-1 bg-gray-600 text-white rounded">حذف</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-gray-400">هیچ کالایی ثبت نشده است.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- خلاصه فاکتور در فروش عمده --}}
{{-- خلاصه فاکتور برای عمده و پرچون --}}
@if (count($items) > 0)
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 space-y-4">

        {{-- مجموع کل قبل از تخفیف --}}
        <div class="flex items-center justify-between">
            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">💰 مجموع کل فاکتور:</span>
            <span class="text-xl font-extrabold text-blue-600 dark:text-blue-400">
                {{ number_format(collect($items)->sum('total')) }} افغانی
            </span>
        </div>

        {{-- تخفیف (برای عمده و پرچون) --}}
        <div class="flex items-center justify-between">
            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">🎁 تخفیف:</span>
            <input wire:model.lazy="discount" type="number" min="0"
                class="w-40 border rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-gray-50 dark:bg-gray-800 dark:text-gray-100"
                placeholder="0" />
        </div>

        {{-- مجموع بعد از تخفیف --}}
        <div class="flex items-center justify-between">
            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">✅ مبلغ نهایی:</span>
            <span class="text-xl font-extrabold text-green-600 dark:text-green-400">
                {{ number_format(max(collect($items)->sum('total') - $discount, 0)) }} افغانی
            </span>
        </div>

        {{-- مبلغ رسید (فقط عمده) --}}
        @if ($saleType === 'wholesale')
            <div class="flex items-center justify-between">
                <span class="text-lg font-bold text-gray-800 dark:text-gray-200">💵 مبلغ رسید:</span>
                <input wire:model.lazy="receivedAmount" type="number" min="0"
                    class="w-40 border rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-50 dark:bg-gray-800 dark:text-gray-100"
                    placeholder="0" />
            </div>

            {{-- باقیمانده (فقط عمده) --}}
            <div class="flex items-center justify-between">
                <span class="text-lg font-bold text-gray-800 dark:text-gray-200">🧾 باقیمانده:</span>
                <span class="text-xl font-extrabold text-red-600 dark:text-red-400">
                    {{ number_format(max((collect($items)->sum('total') - $discount) - $receivedAmount, 0)) }} افغانی
                </span>
            </div>
        @endif

    </div>
@endif

                {{-- دکمه‌های پایانی --}}
                <div class="flex gap-3">
                    <x-filament::button wire:click="finalizeInvoice" color="success" class="px-4 py-2 rounded-lg text-sm">
                        ثبت فاکتور
                    </x-filament::button>

                    <x-filament::button wire:click="printInvoice" color="info" class="px-4 py-2 rounded-lg text-sm">
                        چاپ فاکتور
                    </x-filament::button>
                </div>

            </div>
        </div>
    </div>

    {{-- اسکریپت Livewire برای چاپ --}}
    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('download-invoice', (data) => {
                    window.open(data.url, '_blank');
                });
            });
        </script>
    @endpush

</x-filament-panels::page>
