<div class="px-5">
    <!-- دکمه‌های دسته‌بندی -->
    <div class="grid grid-cols-1 w-[1200px] md:grid-cols-4 lg:grid-cols-4 gap-3 mb-5">
        <button wire:click="selectCategory('customers')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارشات مالی
            مشتریان</button>
        <button wire:click="selectCategory('accounts')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارش حسابات و
            صندوق</button>
        <button wire:click="selectCategory('transactions')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارشات تراکنش های
            معاملات</button>
        <button wire:click="selectCategory('management')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارشات مدیریتی و
            تحلیلی</button>
    </div>

    <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <!-- select زیرشاخه -->
        @if($selectedCategory)
        <div class="mb-5 relative w-full">
            <select wire:model.live="selectedSubCategory"
                class="border bg-transparent border-[#8C8C8C] rounded-[12px] px-4 py-2 pt-[13px] pr-[9px] pl-[9px] pb-[13px] w-full appearance-none">
                <option value="">انتخاب زیرشاخه</option>
                @foreach($subCategories[$selectedCategory] as $sub)
                <option value="{{ $sub }}">{{ $sub }}</option>
                @endforeach
            </select>

            <!-- آیکون سفارشی -->
            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓" class="w-4 h-4">
            </div>
        </div>
        @endif

        <!-- نمایش محتوای زیرشاخه -->
        @if($selectedSubCategory)
            @switch($selectedSubCategory)
                @case('گزارش بیلانس مشتریان')
                    <!-- جدول گزارش بیلانس مشتریان -->
                    <div class="overflow-x-auto w-full mt-4">
                        <div class="max-h-[600px] overflow-y-auto">
                            <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500">
                                <thead class="bg-[#2B65E5] text-white text-[16px] vazir h-16 sticky top-0">
                                    <tr>
                                        <th class="px-4 py-4 font-bold w-16">
                                            <span class="border border-white px-2 py-1 rounded-lg">#</span>
                                        </th>
                                        <th class="px-4 py-4 font-bold">نمبرحساب</th>
                                        <th class="px-4 py-4 font-bold">نام حساب</th>
                                        <th class="px-4 py-4 font-bold">آخرین تاریخ</th>
                                        <th class="px-4 py-4 font-bold">دالر</th>
                                        <th class="px-4 py-4 font-bold">افغانی</th>
                                        <th class="px-4 py-4 font-bold">تومان</th>
                                        <th class="px-4 py-4 font-bold">کلدار</th>
                                        <th class="px-4 py-4 font-bold">یورو</th>
                                        <th class="px-4 py-4 font-bold">درهم</th>
                                        <th class="px-4 py-4 font-bold">لیره</th>
                                        <th class="px-4 py-4 font-bold">یوان</th>
                                        @php
                                        $latestExchangeRate = \App\Models\Sarafi\ExchangeRates::latest()->first();
                                        $sourceCurrency = $latestExchangeRate->source_currency ?? 'دالر';
                                        @endphp
                                        <th class="px-4 py-4 font-bold">بیلانس به {{ $sourceCurrency }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports as $index => $report)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-4">
                                            <span class="px-2 py-1 rounded-lg">{{ $index + 1 }}</span>
                                        </td>
                                        <td class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $report['account_number'] }}</td>
                                        <td class="px-4 py-4">{{ $report['fullname'] }}</td>
                                        <td class="px-3 py-4">
                                            {{ $report['last_date'] ? \Carbon\Carbon::parse($report['last_date'])->format('Y/m/d') : '-' }}
                                        </td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['usd'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['afn'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['irr'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['pkr'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['eur'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['aed'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['try'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['cny'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 font-medium text-left">{{ number_format($report['total_balance'], 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="14" class="px-4 py-8 text-center text-gray-500">
                                            هیچ داده‌ای یافت نشد
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div> <!-- بستن div اول -->

                    <!-- بخش انتخاب مشتری و نمایش نمودار -->
                    <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5"
                        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                        <div class="w-full">
                            <div class="md:w-1/2">
                                <div class="flex-1">
                                    <div class="relative w-full">
                                        <label class="block text-[16px] font-medium text-black mb-1 vazir">نمبر حساب</label>
                                        <div x-data="{
                                            searchValue: '',
                                            selectedId: @entangle('selectedAccount'),
                                            customers: @js($customers->toArray()),
                                            handleSelect(event) {
                                                const selected = this.customers.find(
                                                    c => event.target.value === `${c.account_number} - ${c.fullname}`
                                                );
                                                if (selected) {
                                                    this.selectedId = selected.id;
                                                    this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                                    $wire.selectCustomer(selected.id);
                                                    $wire.set('search', selected.fullname);
                                                } else {
                                                    this.selectedId = null;
                                                    this.searchValue = '';
                                                    $wire.set('selectedAccount', null);
                                                    $wire.set('search', '');
                                                }
                                            },
                                            updateDisplay() {
                                                const selected = this.customers.find(c => c.id === this.selectedId);
                                                this.searchValue = selected ? `${selected.account_number} - ${selected.fullname}` : '';
                                            }
                                        }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())"
                                        class="relative w-full">
                                        <input list="customersList" x-model="searchValue" @change="handleSelect"
                                            placeholder="جستجو یا انتخاب حساب..."
                                            class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                            autocomplete="off">
                                        <datalist id="customersList">
                                            @foreach ($customers as $customer)
                                            <option value="{{ $customer->account_number }} - {{ $customer->fullname }}">
                                            @endforeach
                                        </datalist>
                                        @if (empty($selectedAccount))
                                        <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                            <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                        </div>
                                        @endif
                                    </div>
                                    @error('selectedAccount')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- نمایش نمودار و موجودی -->
                            @if($selectedCustomerId && count($currencyPercentages) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-6">
                                <!-- نمودار Pie -->
                                <div class="bg-white p-6 rounded-[12px] shadow-lg">
                                    <h3 class="text-[18px] font-bold text-center mb-4 vazir">درصد موجودی هر ارز</h3>
                                    <div class="relative w-64 h-64 mx-auto">
                                        <canvas id="currencyPieChart" width="256" height="256"></canvas>
                                    </div>
                                </div>
                                
                                <!-- لیست موجودی‌ها -->
                                <div class="space-y-4">
                                    <div class="w-full h-[79px] flex flex-col md:flex-row items-center justify-between p-6 bg-[#2563EB] text-white text-[16px] rounded-[12px]">
                                        <p class="vazir font-bold">ارزش کل موجودی</p>
                                        <p class="vazir font-bold">
                                            @php
                                                $totalUSD = 0;
                                                foreach($selectedCustomerBalance as $balance) {
                                                    $totalUSD += $balance['balance_usd'];
                                                }
                                            @endphp
                                            {{ number_format($totalUSD, 2) }} 
                                            <span>دالر</span>
                                        </p>
                                    </div>
                                    
                                    @foreach($currencyPercentages as $currencyCode => $data)
                                    <div class="w-full h-[79px] flex flex-col md:flex-row items-center justify-between p-6 bg-transparent border border-[#2563EB] text-black text-[16px] rounded-[12px]">
                                        <div class="flex items-center gap-3">
                                            <div class="w-4 h-4 rounded-full" style="background-color: {{ $data['color'] }}"></div>
                                            <span class="vazir font-medium">{{ $data['currency_name'] }}</span>
                                        </div>
                                        <div class="text-left">
                                            <p class="vazir font-bold">{{ number_format($data['balance'], 2) }}</p>
                                            <p class="vazir text-sm text-gray-600">{{ $data['percentage'] }}%</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @elseif($selectedCustomerId)
                            <div class="text-center py-8">
                                <p class="text-gray-500 vazir">این مشتری موجودی ندارد</p>
                            </div>
                            @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 vazir">لطفاً یک مشتری انتخاب کنید</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @break

                @default
                    <div class="border p-5 rounded bg-gray-50">
                        <h3 class="font-bold text-lg mb-3">{{ $selectedSubCategory }}</h3>
                        <p>این گزارش در حال توسعه می‌باشد</p>
                    </div>
            @endswitch
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('livewire:init', () => {
    let pieChart = null;
    
    console.log('Chart.js initialized');
    
    function initializeChart() {
        const ctx = document.getElementById('currencyPieChart');
        if (!ctx) {
            console.log('Canvas not found, retrying...');
            setTimeout(initializeChart, 100);
            return;
        }
        
        console.log('Canvas found, initializing chart...');
        
        // حذف نمودار قبلی اگر وجود دارد
        if (pieChart) {
            pieChart.destroy();
        }
        
        // داده‌های نمونه برای تست
        const sampleData = {
            labels: ['افغانی', 'دالر', 'تومان'],
            datasets: [{
                data: [50, 30, 20],
                backgroundColor: ['#2563EB', '#DD2424', '#61B138'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        };
        
        pieChart = new Chart(ctx, {
            type: 'pie',
            data: sampleData,
            options: {
                responsive: false,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true,
                        labels: {
                            font: {
                                family: 'Vazir, sans-serif',
                                size: 12
                            },
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        rtl: true,
                        bodyFont: {
                            family: 'Vazir, sans-serif'
                        },
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value}%`;
                            }
                        }
                    }
                }
            }
        });
        
        console.log('Chart initialized successfully');
    }
    
    // راه‌اندازی اولیه نمودار
    setTimeout(initializeChart, 500);
    
    // گوش دادن به تغییرات Livewire
    Livewire.hook('commit', ({ component, succeed }) => {
        succeed(({ snapshot, effect }) => {
            if (component.get('currencyPercentages') && Object.keys(component.get('currencyPercentages')).length > 0) {
                console.log('Currency percentages updated:', component.get('currencyPercentages'));
                setTimeout(updateChartWithData, 300);
            }
        });
    });
    
    function updateChartWithData() {
        const percentages = @this.currencyPercentages;
        console.log('Updating chart with data:', percentages);
        
        if (!percentages || Object.keys(percentages).length === 0) {
            console.log('No data to update chart');
            return;
        }
        
        const ctx = document.getElementById('currencyPieChart');
        if (!ctx || !pieChart) {
            console.log('Chart not ready for update');
            return;
        }
        
        const labels = [];
        const data = [];
        const backgroundColors = [];
        
        Object.entries(percentages).forEach(([currency, info]) => {
            labels.push(info.currency_name);
            data.push(info.percentage);
            backgroundColors.push(info.color);
        });
        
        console.log('Chart data:', { labels, data, backgroundColors });
        
        // به‌روزرسانی داده‌های نمودار
        pieChart.data.labels = labels;
        pieChart.data.datasets[0].data = data;
        pieChart.data.datasets[0].backgroundColor = backgroundColors;
        pieChart.update();
        
        console.log('Chart updated successfully');
    }
    
    // اگر از ابتدا داده وجود دارد، نمودار را به‌روزرسانی کن
    @if(count($currencyPercentages) > 0)
        setTimeout(updateChartWithData, 1000);
    @endif
});
</script>
@endpush