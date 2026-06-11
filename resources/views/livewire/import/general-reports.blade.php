<div class="filament-page vazir text-xl">

    <div class="space-y-3">
        <h1 class="text-4xl font-medium yekan">
            سیستم گزارش‌گیری جامع
        </h1>

        <p class="text-xl text-gray-600 max-w-2xl vazir ">
            مدیریت و تحلیل داده‌های مالی با قابلیت فیلتر پیشرفته و خروجی حرفه‌ای
        </p>
    </div>

    <div class="mx-auto max-w-8xl space-y-8 py-8 px-4 sm:px-6 lg:px-8">

        <!-- Main Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">

            <!-- Sidebar Filters -->
            <div class="lg:col-span-1 space-y-6">

                <!-- Report Type Selector -->
                <div
                    class="w-[1150px] max-w-[1150px] bg-gradient-to-br from-gray-100 to-gray-200 border-l-4 border-pink-500 p-6 rounded-xl shadow-lg transition-all duration-300">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center gap-2 yekan vazir ">
                        نوع گزارش
                    </h3>

                    @php
                    $reportTypes = [
                    'withdraw_log' => ['icon' => 'fa-solid fa-arrow-up-from-bracket', 'label' => 'برداشت‌ها', 'color' =>
                    'pink'],
                    'loan' => ['icon' => 'fa-solid fa-bank', 'label' => 'بردگی‌ها', 'color' => 'red'],
                    'sell' => ['icon' => 'fa-solid fa-tags', 'label' => 'فروش‌ها', 'color' => 'teal'],
                    'buy' => ['icon' => 'fa-solid fa-cart-plus', 'label' => 'خریدها', 'color' => 'indigo'],
                    'transaction' => ['icon' => 'fa-solid fa-exchange-alt', 'label' => 'تراکنش‌ها', 'color' => 'blue'],
                    'company_payment' => ['icon' => 'fa-solid fa-building', 'label' => 'پرداخت شرکت', 'color' =>
                    'orange'],
                    ];
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 ">
                        @foreach($reportTypes as $type => $info)
                        <button wire:click="$set('reportType', '{{ $type }}')" class="flex items-center gap-3 p-4 rounded-2xl border text-sm font-medium transition-all duration-300 shadow-sm hover:scale-105
                            @if($reportType === $type)
                                bg-gradient-to-r from-{{ $info['color'] }}-500 to-{{ $info['color'] }}-600 text-white border-{{ $info['color'] }}-500 shadow-md
                            @else
                                bg-white text-gray-700 border-gray-200 hover:bg-{{ $info['color'] }}-50 hover:border-{{ $info['color'] }}-300
                            @endif
                        ">
                            <i class="{{ $info['icon'] }} text-lg"></i>
                            <span class="flex-1 text-xl font-medium ">{{ $info['label'] }}</span>
                            @if($reportType === $type)
                            <span class="ml-auto text-white font-bold ">✓</span>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-8">

                    <!-- Filters Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div
                            class="bg-gradient-to-br from-black to-blue-400 border-l-4 border-pink-500 text-white p-6 rounded-xl shadow-lg transition-all duration-300">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <span class="text-2xl">🎛️</span>
                                فیلترهای پیشرفته
                            </h2>
                        </div>

                        <div class="p-6 space-y-6">

                            <!-- Basic Filters -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                <!-- Currency Select -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <span class="text-green-600">💵</span>
                                        واحد پول
                                    </label>
                                    <select wire:model.live="currency"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه واحدها</option>
                                        <option value="افغانی">🇦🇫 افغانی</option>
                                        <option value="دالر">🇺🇸 دالر</option>
                                    </select>
                                </div>

                                <!-- Search -->
                                <div class="space-y-2 mt-7">
                                    <div class="relative flex">
                                        <input type="text" wire:model.live="search" placeholder="جستجو بر اساس نام شخص"
                                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt="">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Dynamic Filters Based on Report Type -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                                <!-- Customer Filter (for loan, sell, transaction) -->
                                @if(in_array($reportType, ['loan', 'sell', 'transaction']))
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">مشتری</label>
                                    <select wire:model.live="customerId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه مشتریان</option>
                                        @foreach($customers as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <!-- Staff Filter (for withdraw_log, transaction) -->
                                @if(in_array($reportType, ['withdraw_log', 'transaction']))
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">کارمند</label>
                                    <select wire:model.live="staffId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه کارمندان</option>
                                        @foreach($staffs as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <!-- Company Filter (for buy, company_payment) -->
                                @if(in_array($reportType, ['buy', 'company_payment']))
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">شرکت</label>
                                    <select wire:model.live="companyId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه شرکت‌ها</option>
                                        @foreach($companies as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <!-- Type Filter (for withdraw_log, loan, transaction) -->
                                @if(in_array($reportType, ['withdraw_log', 'loan', 'transaction']))
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">نوع</label>
                                    <select wire:model.live="type"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه انواع</option>
                                        @if($reportType === 'withdraw_log')
                                        <option value="electricity">برق</option>
                                        <option value="rent">کرایه</option>
                                        <option value="water">مالیه</option>
                                        <option value="food">غذا</option>
                                        <option value="salary">معاش کارمند</option>
                                        <option value="transportation">بارچلانی چین</option>
                                        <option value="other">متفرقه</option>
                                        @elseif($reportType === 'loan')
                                        <option value="بردگی">بردگی</option>
                                        <option value="رسید">رسید</option>
                                        @elseif($reportType === 'transaction')
                                        <option value="رسید">رسید</option>
                                        <option value="برداشت">برداشت</option>
                                        @endif
                                    </select>
                                </div>
                                @endif





                                <!-- Start Date -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">از تاریخ</label>
                                    <div class="relative">
                                        <input type="text" wire:model.live="startDateJalali" placeholder="1403/01/01"
                                            class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm persian-datepicker"
                                            id="startDatePicker">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            📅
                                        </div>
                                    </div>
                                    @error('startDateJalali')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- End Date -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">تا تاریخ</label>
                                    <div class="relative">
                                        <input type="text" wire:model.live="endDateJalali" placeholder="1403/01/31"
                                            class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm persian-datepicker"
                                            id="endDatePicker">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            📅
                                        </div>
                                    </div>
                                    @error('endDateJalali')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Results Section -->
                    <div class="rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

                        <!-- Table Header -->
                        <div
                            class="bg-gradient-to-br from-black to-blue-400 border-l-4 border-pink-500 text-white px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <span class="text-xl">📈</span>
                                    نتایج گزارش - {{ $reportTypes[$reportType]['label'] ?? 'نامشخص' }}
                                    <span class="bg-primary-500 text-white text-sm px-3 py-1 rounded-full">
                                        {{ number_format($reports->total()) }} مورد
                                    </span>
                                </h3>
                                <div class="flex items-center gap-2 text-sm text-white">
                                    <span>📊</span>
                                    @if($reports->total() > 0)
                                    نمایش {{ $reports->firstItem() }} - {{ $reports->lastItem() }} از {{
                                    $reports->total() }}
                                    @else
                                    هیچ داده‌ای یافت نشد
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gradient-to-r from-primary-50 to-primary-100">
                                    <tr>
                                        @switch($reportType)
                                        @case('withdraw_log')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع</th>
                                        
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            توضیحات</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ ثبت</th>
                                        @break

                                        @case('loan')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مشتری</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ</th>
                                            <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            توضیحات</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>
                                        @break


                                        @case('sell')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            شماره فاکتور</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع فروش</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مشتری</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت کل</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تخفیف</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ ثبت</th>
                                        @break

                                        @case('buy')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            بارکد</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نام کالا</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            شرکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت کل</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تعداد</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ واردات</th>
                                        @break

                                        @case('transaction')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            شخص</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نام شخص</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ تراکنش</th>
                                        @break

                                        @case('company_payment')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            شرکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            کل بدهی</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            پرداخت شده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            باقی مانده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ ثبت</th>
                                        @break

                                        @endswitch
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($reports as $report)
                                    <tr
                                        class="hover:bg-gradient-to-r hover:from-primary-50 hover:to-primary-25 transition-all duration-200 group">
                                        @switch($reportType)
                                        @case('withdraw_log')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @php
                                            $typeTranslations = [
                                            'electricity' => 'برق',
                                            'rent' => 'کرایه',
                                            'water' => 'مالیه',
                                            'food' => 'غذا',
                                            'salary' => 'معاش کارمند',
                                            'transportation' => 'بارچلانی چین',
                                            'other' => 'متفرقه',
                                            ];
                                            @endphp

                                            {{ $typeTranslations[$report->type] ?? $report->type }}
                                        </td>

                                    
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900">{{ number_format($report->amount)
                                                }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                {{ $report->currency }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                            {{ $report->description ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->created_at ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d')
                                            : '-' }}
                                        </td>
                                        @break
                                        @case('loan')
                                    <tr
                                        class="hover:bg-gradient-to-r hover:from-primary-50 hover:to-primary-25 transition-all duration-200 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $report->type === 'برد' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $report->type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $report->customer->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900">
                                                @if($report->type === 'برد')
                                                {{ number_format($report->amount, 2) }}
                                                @else
                                                {{ number_format($report->amount, 2) }}
                                                @endif
                                            </span>
                                        </td>

                                         <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                {{ $report->description }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                {{ $report->currency }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'
                                            }}
                                        </td>
                                    </tr>
                                    @break


                                    @case('sell')
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        #{{ $report->invoice_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                            {{ $report->sale_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $report->customer->fullname ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-gray-900">{{ number_format($report->total_price)
                                            }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-orange-600">{{ number_format($report->discount)
                                            }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report->created_at ?
                                        \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d')
                                        : '-' }}
                                    </td>
                                    @break

                                    @case('buy')
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $report->barcode }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $report->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-medium text-gray-900">{{ $report->company->name ?? '-'
                                            }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-gray-900">{{ number_format($report->total_price)
                                            }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                            {{ $report->currency }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-blue-600">{{
                                            number_format($report->all_exist_number) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report->import_date ?
                                        \Morilog\Jalali\Jalalian::fromDateTime($report->import_date)->format('Y/m/d')
                                        : '-' }}
                                    </td>
                                    @break

                                    @case('transaction')
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $report->type === 'برداشت' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $report->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            @if($report->customer_id) مشتری
                                            @elseif($report->staff_id) کارمند
                                            @elseif($report->sarafi_id) صرافی
                                            @else دوکان @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $report->customer->fullname ?? $report->staff->fullname ??
                                        $report->sarafi->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-gray-900">{{ number_format($report->amount)
                                            }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                            {{ $report->currency }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report->created_at ?
                                        \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d')
                                        : '-' }}
                                    </td>
                                    @break

                                    @case('company_payment')
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $report->company->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                            {{ $report->currency }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-gray-900">{{ number_format($report->total_debt)
                                            }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-green-600">{{
                                            number_format($report->paid_amount) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-red-600">{{ number_format($report->remaining)
                                            }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report->created_at ?
                                        \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d')
                                        : '-' }}
                                    </td>
                                    @break

                                    @endswitch
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-4">
                                                <div
                                                    class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <span class="text-3xl">📭</span>
                                                </div>
                                                <div class="space-y-2">
                                                    <h4 class="text-lg font-semibold text-gray-700">داده‌ای یافت نشد
                                                    </h4>
                                                    <p class="text-gray-500 text-sm">هیچ رکوردی با فیلترهای فعلی مطابقت
                                                        ندارد</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($reports->hasPages())
                        <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    صفحه {{ $reports->currentPage() }} از {{ $reports->lastPage() }}
                                </div>
                                <div class="flex gap-2">
                                    {{ $reports->links() }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 justify-center pr-7">
                    <button wire:click="exportToExcel"
                        class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-green-900 to-green-900 text-white p-3 py-3 rounded-xl font-medium hover:from-green-900 hover:to-green-900 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <img src="{{ asset('assets/sarafi/all_icon/excel.png') }}" class="h-10 w-10" alt="">
                        خروجی اکسیل
                    </button>

                    <button wire:click="printReport"
                        class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-red-900 via-red-900 to-red-900 text-white py-3 px-4 rounded-2xl font-semibold shadow-md hover:shadow-lg transition-all duration-300"
                        wire:loading.attr="disabled" wire:target="printReport">
                        <span wire:loading.remove wire:target="printReport">
                            <img src="{{ asset('assets/sarafi/all_icon/pdf.png') }}" class="h-10 w-10" alt="">
                        </span>
                        <span wire:loading wire:target="printReport">
                            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 2v4m0 12v4m8-10h-4M6 12H2m15.364-7.364l-2.828 2.828M7.464 17.536l-2.828 2.828M17.536 7.464l2.828 2.828M4.464 19.536l2.828-2.828" />
                            </svg>
                        </span>
                        خروجی پی دی اف
                    </button>

                    <button wire:click="resetFilters"
                        class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-gray-900 to-gray-900 text-white p-3 rounded-xl font-medium hover:from-gray-900 hover:to-gray-900 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <span>🔄</span>
                        بازنشانی فیلترها
                    </button>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ساده‌ترین راه‌حل - تقویم ساده
            function createSimpleDatePicker(inputId) {
                const input = document.getElementById(inputId);
                
                input.addEventListener('click', function() {
                    // ایجاد یک تقویم ساده
                    const today = new persianDate();
                    const year = today.year();
                    const month = today.month();
                    
                    let calendarHTML = `
                        <div style="position: absolute; z-index: 1000; background: white; border: 1px solid #ccc; padding: 10px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            <div style="text-align: center; margin-bottom: 10px;">
                                <button onclick="prevMonth('${inputId}')">‹</button>
                                <span style="margin: 0 10px;">${year}/${month + 1}</span>
                                <button onclick="nextMonth('${inputId}')">›</button>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(7, 30px); gap: 2px;">
                    `;
                    
                    // اضافه کردن روزهای هفته
                    const days = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];
                    days.forEach(day => {
                        calendarHTML += `<div style="text-align: center; font-weight: bold;">${day}</div>`;
                    });
                    
                    // اضافه کردن روزهای ماه
                    const daysInMonth = new persianDate([year, month, 1]).daysInMonth();
                    for (let day = 1; day <= daysInMonth; day++) {
                        calendarHTML += `
                            <div style="text-align: center; padding: 5px; cursor: pointer; border-radius: 4px;" 
                                 onclick="selectDate('${inputId}', ${year}, ${month + 1}, ${day})">
                                ${day}
                            </div>`;
                    }
                    
                    calendarHTML += `</div></div>`;
                    
                    // حذف تقویم قبلی اگر وجود دارد
                    const existingCalendar = document.getElementById('simpleCalendar');
                    if (existingCalendar) {
                        existingCalendar.remove();
                    }
                    
                    // اضافه کردن تقویم جدید
                    const calendarDiv = document.createElement('div');
                    calendarDiv.id = 'simpleCalendar';
                    calendarDiv.innerHTML = calendarHTML;
                    calendarDiv.style.position = 'absolute';
                    calendarDiv.style.zIndex = '1000';
                    calendarDiv.style.top = (input.offsetTop + input.offsetHeight) + 'px';
                    calendarDiv.style.left = input.offsetLeft + 'px';
                    
                    document.body.appendChild(calendarDiv);
                });
            }

            // تعریف توابع全局
            window.selectDate = function(inputId, year, month, day) {
                const dateString = `${year}/${month.toString().padStart(2, '0')}/${day.toString().padStart(2, '0')}`;
                document.getElementById(inputId).value = dateString;
                
                if (inputId === 'startDatePicker') {
                    @this.set('startDateJalali', dateString);
                } else if (inputId === 'endDatePicker') {
                    @this.set('endDateJalali', dateString);
                }
                
                // حذف تقویم
                const calendar = document.getElementById('simpleCalendar');
                if (calendar) {
                    calendar.remove();
                }
            };

            // مقداردهی اولیه
            createSimpleDatePicker('startDatePicker');
            createSimpleDatePicker('endDatePicker');
        });
    </script>
    @endpush

    @push('styles')
    <!-- ✅ Tailwind از CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- ✅ تنظیمات Tailwind -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#EEF2FF',
                            500: '#6366F1',
                            600: '#4F46E5',
                        },
                    },
                    fontFamily: {
                        vazir: ['Vazir', 'sans-serif'],
                        shabnam: ['Shabnam', 'sans-serif'],
                        yekan: ['DimaYekan', 'sans-serif'],
                        amiri: ['Yekan-Regular', 'sans-serif'],
                        times: ['Times', 'serif'],
                    },
                },
            },
        }
    </script>

    <!-- ✅ فونت‌ها و کلاس‌ها -->
    <style>
        @font-face {
            font-family: "DimaYekan";
            src: url("/fonts/Yekan-Regular.ttf") format("truetype");
        }

        @font-face {
            font-family: "times";
            src: url("/fonts/times.ttf") format("truetype");
        }

        @font-face {
            font-family: "vazir";
            src: url("/fonts/Vazir.ttf") format("truetype");
        }

        @font-face {
            font-family: "shabnam";
            src: url("/fonts/Shabnam-Medium.ttf") format("truetype");
        }

        @font-face {
            font-family: "Mj_Afrigha";
            src: url("/fonts/Mj_Afrigha.ttf") format("truetype");
        }

        @font-face {
            font-family: "Yekan-Regular";
            src: url("/fonts/Yekan-Regular.ttf") format("truetype");
        }

        /* کلاس‌های کمکی برای انتخاب سریع فونت */
        .yekan {
            font-family: "DimaYekan", sans-serif;
        }

        .shabnam {
            font-family: "shabnam", sans-serif;
        }

        .Mj_Afrigha {
            font-family: "Mj_Afrigha", sans-serif;
        }

        .vazir {
            font-family: "vazir", sans-serif;
        }

        .amiri {
            font-family: "Yekan-Regular", sans-serif;
        }

        .times {
            font-family: "times", serif;
        }
    </style>
    @endpush
</div>