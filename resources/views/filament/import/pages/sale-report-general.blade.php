<x-filament-panels::page>
    <div class="space-y-6">

        <!-- فیلتر محصول -->
        <div class="bg-white rounded-xl shadow p-4 fade-in">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                <!-- فرم جستجو -->
                <form wire:submit.prevent="generateReport" class="w-full md:w-1/3 relative">
                    <input type="text" wire:model.defer="product_name" placeholder="جستجو بر اساس نام محصول..."
                        class="w-full border rounded-lg px-4 py-2 pr-10 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                    <button type="submit" class="absolute right-2 top-2 text-gray-500">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <!-- دکمه‌ها -->
                <div class="flex gap-2 mt-2 md:mt-0">
                    <x-filament::button wire:click="showAllProducts" color="gray">
                        نمایش همه
                    </x-filament::button>
                </div>
            </div>
        </div>

        <!-- جدول گزارش -->
        <div class="bg-white rounded-xl shadow overflow-hidden fade-in">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-3 px-4 text-right font-semibold text-gray-700">نام محصول</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700">تعداد فروخته شده</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700">پرچون</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700">عمده</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700">موجودی گدام</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700">موجودی دوکان</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700">مجموع سود</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700">مجموع زیان</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700">موجودی کل</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($report as $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $item['name'] }}</td>
                            <td class="py-3 px-4 text-center text-gray-700">{{
                                number_format($item['total_quantity_sold']) }}</td>
                            <td class="py-3 px-4 text-center text-gray-700">{{ number_format($item['retail_quantity'])
                                }}</td>
                            <td class="py-3 px-4 text-center text-gray-700">{{
                                number_format($item['wholesale_quantity']) }}</td>
                            <td class="py-3 px-4 text-center text-gray-700">{{ number_format($item['all_exist_number'])
                                }}</td>
                            <td class="py-3 px-4 text-center text-gray-700">{{
                                number_format($item['all_exist_number_inventory']) }}</td>
                            <td class="py-3 px-4 text-center text-green-600 font-semibold">{{
                                number_format($item['total_profit']) }}</td>
                            <td class="py-3 px-4 text-center text-red-600 font-semibold">{{
                                number_format($item['total_loss']) }}</td>
                            <td class="py-3 px-4 text-center text-blue-600 font-semibold">{{
                                number_format($item['total_in_warehouse']) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <x-heroicon-o-inbox class="w-12 h-12 text-gray-400 mb-3" />
                                    <p class="text-lg font-medium">هیچ محصولی یافت نشد</p>
                                    @if($product_name)
                                    <p class="text-sm text-gray-400 mt-1">برای "{{ $product_name }}" نتیجه‌ای پیدا نشد
                                    </p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- کارت‌ها -->
        @if(count($report) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 fade-in">
            <x-filament::card wire:click="filterTopProduct" class="cursor-pointer border-l-4 border-l-blue-500">
                <div class="flex items-start gap-4">
                    <div class="bg-blue-100 p-3 rounded-full shrink-0">
                        <x-heroicon-o-trophy class="w-6 h-6 text-blue-600" />
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800 text-lg mb-1">پرفروش‌ترین محصول</h3>
                        <p class="text-gray-600 mb-3">
                            {{ $topProduct['name'] ?? '---' }}
                            ({{ number_format($topProduct['total_quantity_sold'] ?? 0) }} فروش)
                        </p>
                    </div>
                </div>
            </x-filament::card>

            <x-filament::card wire:click="filterLeastProduct" class="cursor-pointer border-l-4 border-l-amber-500">
                <div class="flex items-start gap-4">
                    <div class="bg-amber-100 p-3 rounded-full shrink-0">
                        <x-heroicon-o-chart-bar class="w-6 h-6 text-amber-600" />
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800 text-lg mb-1">کم‌فروش‌ترین محصول</h3>
                        <p class="text-gray-600 mb-3">
                            {{ $leastProduct['name'] ?? '---' }}
                            ({{ number_format($leastProduct['total_quantity_sold'] ?? 0) }} فروش)
                        </p>
                    </div>
                </div>
            </x-filament::card>
        </div>
        @endif

        @if($topWholesaleCustomer)
        <x-filament::card class="border-l-4 border-l-green-500">
            <div class="flex items-start gap-4">
                <div class="bg-green-100 p-3 rounded-full shrink-0">
                    <x-heroicon-o-user-group class="w-6 h-6 text-green-600" />
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 text-lg mb-2">پرفروش‌ترین مشتری عمده</h3>
                    <p class="text-gray-600 mb-4">نام مشتری:
                        <span class="font-semibold text-gray-800">{{ $topWholesaleCustomer->buyer_name }}</span>
                    </p>
                    <div class="bg-gray-50 p-4 rounded-lg flex justify-between items-center">
                        <span class="text-gray-600">مجموع خرید:</span>
                        <span class="font-bold text-blue-600 text-lg">
                            {{ number_format($topWholesaleCustomer->total_spent) }}
                        </span>
                    </div>
                    <div class="bg-gray-50 p-4 mt-2 rounded-lg flex justify-between items-center">
                        <span class="text-gray-600">تعداد کل خرید:</span>
                        <span class="font-bold text-purple-600 text-lg">
                            {{ number_format($topWholesaleCustomer->total_quantity) }}
                        </span>
                    </div>
                </div>
            </div>
        </x-filament::card>
        @endif


    </div>
</x-filament-panels::page>