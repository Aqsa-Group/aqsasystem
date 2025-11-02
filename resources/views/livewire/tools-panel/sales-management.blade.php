<div x-data="{ saleType: @entangle('saleType') }" class="space-y-6 p-4">

    {{-- ردیف دکمه‌ها و انتخاب مشتری --}}
    <div class="flex items-center justify-between mb-3 gap-6">
        <div class="flex gap-3 flex-1 max-w-xs">
            <button @click="saleType = 'retail'; $wire.switchToRetail();"
                class="flex-1 py-3 text-base font-bold rounded-xl shadow hover:scale-105 transition bg-red-600 hover:bg-red-700 text-white"
                x-bind:class="saleType === 'retail' ? 'ring-2 ring-red-400' : ''">
                🛍️ فروش پرچون
            </button>

            <button @click="saleType = 'wholesale'; $wire.switchToWholesale();"
                class="flex-1 py-3 text-base font-bold rounded-xl shadow hover:scale-105 transition bg-green-600 hover:bg-green-700 text-white"
                x-bind:class="saleType === 'wholesale' ? 'ring-2 ring-gr500.00een-400' : ''">
                🛒 فروش عمده
            </button>
        </div>

        {{-- انتخاب مشتری فقط در فروش عمده --}}
        <template x-if="saleType === 'wholesale'">
            <div class="flex-shrink-0 w-48">
                <label class="block text-gray-700 text-sm mb-1">👤 انتخاب خریدار</label>
                <select wire:model.defer="customer_id"
                    class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 focus:ring-2 focus:ring-green-400">
                    <option value="">انتخاب خریدار...</option>
                    @foreach (\App\Models\Tools\Customer::where('user_id', auth()->guard('tools')->id())->get() as $customer)
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
            <div class="bg-white rounded-xl shadow-md p-4 border border-gray-200 space-y-3">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-1">🏷️ افزودن محصول</h2>

                <form wire:submit.prevent="submitForm" class="space-y-3 relative">
                    <div>
                        <label class="block text-gray-700 text-sm mb-1">اسکن بارکد</label>
                        <input wire:model="barcode" placeholder="اسکن بارکد..."
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50" />
                    </div>

                    <div class="relative">
                        <label class="block text-gray-700 text-sm mb-1">جستجو با نام محصول</label>
                        <input wire:model.debounce.200ms="searchName" placeholder="نام محصول..." autocomplete="off"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50" />
                        @if (!empty($suggestions))
                            <div class="absolute w-full bg-white border rounded-lg shadow mt-1 max-h-40 overflow-y-auto z-50">
                                @foreach ($suggestions as $product)
                                    <div class="px-3 py-2 hover:bg-gray-100 cursor-pointer text-gray-800"
                                        wire:click="selectProduct({{ $product['id'] }})">
                                        {{ $product['product_name'] }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm mb-1">تعداد</label>
                        <input type="number" wire:model="quantity" min="1"
                            class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50" />
                    </div>

                    <button type="submit"
                        class="w-full py-2 text-sm rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">
                        افزودن به فاکتور
                    </button>
                </form>
            </div>
        </div>

        {{-- ستون جدول کالاها و خلاصه فاکتور --}}
        <div class="col-span-2 space-y-4">
            <div class="bg-white rounded-xl shadow-md p-4 border border-gray-200 overflow-x-auto">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-lg font-bold text-gray-800">🛒 لیست کالاهای فاکتور</span>
                    <span class="text-sm text-gray-500">تعداد کالا: {{ count($items) }}</span>
                </div>
                <table class="w-full text-sm text-right">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-2">نام محصول</th>
                            <th class="p-2">تعداد</th>
                            <th class="p-2">قیمت واحد (افغانی)</th>
                            <th class="p-2">مجموع (افغانی)</th>
                            <th class="p-2">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($items as $index => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="p-2 text-gray-800">{{ $item['name'] }}</td>
                                <td class="p-2 flex items-center justify-center gap-2">
                                    <button wire:click="decreaseQuantity({{ $index }})"
                                        class="px-2 bg-red-500 text-white rounded">−</button>
                                    <span>{{ $item['quantity'] }}</span>
                                    <button wire:click="increaseQuantity({{ $index }})"
                                        class="px-2 bg-green-500 text-white rounded">+</button>
                                </td>
                                <td class="p-2 text-gray-600">
                                    <input type="number" min="0" step="0.01" wire:model.lazy="items.{{ $index }}.price"
                                        wire:change="updateItemPrice({{ $index }})"
                                        class="w-20 border rounded px-2 py-1 text-sm bg-gray-50" />
                                </td>
                                <td class="p-2 font-semibold text-blue-600">{{ number_format($item['total'], 2) }}</td>
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

            @if(count($items) > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-gray-800">💰 مجموع کل فاکتور:</span>
                        <span class="text-xl font-extrabold text-blue-600">
                            {{ number_format(collect($items)->sum('total'), 2) }} افغانی
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-gray-800">🎁 تخفیف:</span>
                        <input wire:model.lazy="discount" type="number" min="0" step="0.01"
                            class="w-40 border rounded-lg px-3 py-2 text-lg focus:ring-2 focus:ring-yellow-500 bg-gray-50"
                            placeholder="0" />
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-gray-800">✅ مبلغ نهایی:</span>
                        <span class="text-xl font-extrabold text-green-600">
                            {{ number_format(max(collect($items)->sum('total') - $discount, 0), 2) }} افغانی
                        </span>
                    </div>

                    <template x-if="saleType === 'wholesale'">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-800">💵 مبلغ رسید:</span>
                                <input wire:model.lazy="receivedAmount" type="number" min="0" step="0.01"
                                    class="w-40 border rounded-lg px-3 py-2 text-lg focus:ring-2 focus:ring-green-500 bg-gray-50"
                                    placeholder="0" />
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-800">🧾 باقیمانده:</span>
                                <span class="text-xl font-extrabold text-red-600">
                                    {{ number_format(max((collect($items)->sum('total') - $discount) - $receivedAmount, 0), 2) }} افغانی
                                </span>
                            </div>
                        </div>
                    </template>

                    <template x-if="saleType === 'retail'">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-800">💵 مبلغ دریافتی:</span>
                                <input wire:model.lazy="receivedAmount" type="number" min="0" step="0.01"
                                    class="w-40 border rounded-lg px-3 py-2 text-lg focus:ring-2 focus:ring-green-500 bg-gray-50"
                                    placeholder="0" 
                                    value="{{ number_format(collect($items)->sum('total') - $discount, 2) }}" />
                            </div>
                        </div>
                    </template>

                    <button wire:click="finalizeAndPrintInvoice"
                        class="w-full py-4 rounded-lg font-bold text-white text-lg bg-blue-600 hover:bg-blue-700">
                        ثبت و چاپ فاکتور
                    </button>
                </div>
            @endif
        </div>
    </div>

     @push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
                Livewire.on('download-invoice', (data) => {
                    window.open(data.url, '_blank');
                });
            });
    </script>
    @endpush
</div>