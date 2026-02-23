<div class="filament-page vazir text-xl">
    <div class="space-y-3">
        <h1 class="text-4xl font-medium yekan">
            سیستم گزارش‌گیری جامع
        </h1>
        <p class="text-xl text-gray-600 max-w-2xl vazir">
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
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center gap-2 yekan vazir">
                        نوع گزارش
                    </h3>

                    @php
                    $reportTypes = [
                    'withdraw_salary' => ['icon' => 'fa-solid fa-users', 'label' => 'برداشت‌ها و معاش کارمندان', 'color'
                    => 'purple'],
                    'outside' => ['icon' => 'fa-solid fa-money-bill', 'label' => 'عواید بیرونی', 'color' => 'green'],
                    'accounting' => ['icon' => 'fa-solid fa-file-invoice', 'label' => 'حسابداری', 'color' => 'blue'],
                    'deposit' => ['icon' => 'fa-solid fa-hourglass-half', 'label' => 'تسویه نشده', 'color' => 'orange'],
                    'loan' => ['icon' => 'fa-solid fa-bank', 'label' => 'بردگی‌ها', 'color' => 'red'],
                    'payment' => ['icon' => 'fa-solid fa-receipt', 'label' => 'رسیدها', 'color' => 'purple'],
                    'buy' => ['icon' => 'fa-solid fa-cart-plus', 'label' => 'خریدها', 'color' => 'indigo'],
                    'sell' => ['icon' => 'fa-solid fa-tags', 'label' => 'فروش‌ها', 'color' => 'teal'],
                    'fund' => ['icon' => 'fa-solid fa-cash-register', 'label' => 'گزارش صندوق', 'color' => 'emerald'],
                    ];

                    $userRole = Auth::guard('market')->user()->role ?? null;
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($reportTypes as $type => $info)
                        @if(($userRole === 'warehouse_manager' && $type === 'accounting') || ($userRole !==
                        'warehouse_manager'))
                        <button wire:click="$set('reportType', '{{ $type }}')" class="flex items-center gap-3 p-4 rounded-2xl border text-sm font-medium transition-all duration-300 shadow-sm hover:scale-105
                            @if($reportType === $type)
                                bg-gradient-to-r from-{{ $info['color'] }}-500 to-{{ $info['color'] }}-600 text-white border-{{ $info['color'] }}-500 shadow-md
                            @else
                                bg-white text-gray-700 border-gray-200 hover:bg-{{ $info['color'] }}-50 hover:border-{{ $info['color'] }}-300
                            @endif
                        ">
                            <i class="{{ $info['icon'] }} text-lg"></i>
                            <span class="flex-1 text-xl font-medium">{{ $info['label'] }}</span>
                            @if($reportType === $type)
                            <span class="ml-auto text-white font-bold">✓</span>
                            @endif
                        </button>
                        @endif
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
                                <!-- Market Select -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <span class="text-primary-600">🏪</span>
                                        مارکت
                                    </label>
                                    <select wire:model.live="marketId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه مارکت‌ها</option>
                                        @foreach($markets as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Currency Select -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <span class="text-green-600">💵</span>
                                        واحد پول
                                    </label>
                                    <select wire:model.live="currency"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه واحدها</option>
                                        <option value="AFN">🇦🇫 افغانی</option>
                                        <option value="USD">🇺🇸 دالر</option>
                                        <option value="EUR">🇪🇺 یورو</option>
                                        <option value="IRR">🇮🇷 تومان</option>
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
                                <!-- Staff Filter for combined report -->
                                @if(in_array($reportType, ['withdraw_salary']))
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


                                @if(in_array($reportType, ['accounting', 'deposit']))

                                <!-- 🔴 اضافه کردن فیلتر طبقه -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">طبقه</label>
                                    <select wire:model.live="floor"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه طبقه‌ها</option>
                                        @foreach($floors as $floorItem)
                                        <option value="{{ $floorItem }}">{{ $floorItem }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <!-- Expanses Type Filter for combined report -->
                                @if(in_array($reportType, ['withdraw_salary' ,'outside','fund']))
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">نوع هزینه</label>
                                    <select wire:model.live="expansesType"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه انواع</option>
                                        <option value="کرایه">کرایه</option>
                                        <option value="عواید بیرونی">عواید بیرونی</option>
                                        <option value="تحت الملکی">تحت الملکی</option>
                                        <option value="پول برق">پول برق</option>
                                        <option value="پول آب">پول آب</option>
                                        <option value="صفایی">صفایی</option>
                                    </select>
                                </div>
                                @endif

                                <!-- Shop Filter -->
                                @if(in_array($reportType, ['accounting', 'deposit']))
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">شماره دوکان</label>
                                    <select wire:model.live="shopId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه دوکان‌ها</option>
                                        @foreach($shops as $id => $number)
                                        <option value="{{ $id }}">{{ $number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <!-- Booth Filter -->
                                @if(in_array($reportType, ['accounting', 'deposit', 'sell']))
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">شماره غرفه</label>
                                    <select wire:model.live="boothId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه غرفه‌ها</option>
                                        @foreach($booths as $id => $number)
                                        <option value="{{ $id }}">{{ $number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <!-- Shopkeeper Filter -->
                                @if(in_array($reportType, ['accounting', 'deposit', 'outside', 'loan', 'payment']))
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">دوکاندار</label>
                                    <select wire:model.live="shopkeeperId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه دوکانداران</option>
                                        @foreach($shopkeepers as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <!-- Customer Filter -->
                                @if(in_array($reportType, ['outside', 'loan', 'payment', 'buy', 'sell']))
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

                                <!-- Staff Filter -->
                                @if(in_array($reportType, ['outside', 'loan', 'payment']))
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

                                <!-- Type Filter -->
                                @if(in_array($reportType, ['accounting', 'deposit', 'outside', 'loan']))
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">نوع</label>
                                    <select wire:model.live="type"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه انواع</option>
                                        <option value="دوکان">دوکان</option>
                                        <option value="غرفه">غرفه</option>
                                    </select>
                                </div>
                                @endif

                                <!-- Expanses Type Filter -->
                                @if(in_array($reportType, ['accounting', 'deposit', 'withdraw_log']))
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">نوع هزینه</label>
                                    <select wire:model.live="expansesType"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه انواع</option>
                                        <option value="کرایه">کرایه</option>
                                        <option value="تحت الملکی">تحت الملکی</option>
                                        <option value="پول برق">پول برق</option>
                                        <option value="پول آب">پول آب</option>
                                        <option value="صفایی">صفایی</option>
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
                                        @case('withdraw_salary')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            برداشت از</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            شخص</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            توضیحات</th>
                                        @break


                                        @case('fund')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع مصرف</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>
                                        @break
                                        @case('accounting')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            دوکاندار</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع مصرف</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>

                                        @break

                                        @case('outside')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع شخص</th>
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
                                            تاریخ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            توضیحات</th>
                                        @break

                                        @case('salary')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            کارمند</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            حقوق</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            پرداخت شده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            باقی مانده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قرضه</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ پرداخت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            وضعیت کسر</th>
                                        @break

                                        @case('deposit')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            دوکاندار</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع هزینه</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ کل</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            پرداخت شده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            باقی مانده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ پرداخت</th>
                                        @break

                                        @case('loan')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع شخص</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نام شخص</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ اصلی</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            پرداخت شده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            باقی مانده</th>

                                        <th>
                                            نوع ارز
                                        </th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>

                                        @break

                                        @case('payment')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            کد قرضه</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ پرداخت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ رسید</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            توضیحات</th>
                                        @break

                                        @case('buy')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            فروشنده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع خرید</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت خرید</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ ثبت</th>
                                        @break

                                        @case('sell')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مشتری</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع ملک</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت فروش</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            جزئیات</th>
                                        @break

                                        @case('withdraw_log')
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع هزینه</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            دریافت کننده</th>
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

                                        @endswitch
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($reports as $report)
                                    <tr
                                        class="hover:bg-gradient-to-r hover:from-primary-50 hover:to-primary-25 transition-all duration-200 group">
                                        @switch($reportType)
                                        @case('withdraw_salary')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                    {{ $report->record_type === 'withdraw' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800' }}">
                                                {{ $report->record_type === 'withdraw' ? 'برداشت' : 'معاش' }}
                                            </span>
                                        </td>

                                        {{-- ستون "برداشت از" --}}
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            @if($report->record_type === 'withdraw')
                                            {{ $report->expanses_type ?? '-' }}
                                            @else
                                            {{ $report->reduce_from ?? '-' }}
                                            @endif
                                        </td>

                                        {{-- ستون "شخص" --}}
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            @if($report->record_type === 'withdraw')

                                            @if($report->staff_id)
                                            {{ $report->staff->fullname ?? '-' }}
                                            @elseif($report->customer_id)
                                            {{ $report->customer->name ?? '-' }}
                                            @else
                                            -
                                            @endif
                                            @else
                                            {{-- برای معاش: نام کارمند --}}
                                            {{ $report->staff->fullname ?? '-' }}
                                            @endif
                                        </td>

                                        {{-- ستون "مبلغ" --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900">
                                                {{ number_format($report->record_type === 'withdraw' ? $report->amount :
                                                $report->paid) }}
                                            </span>
                                        </td>

                                        {{-- ستون "واحد پول" --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                @switch($report->currency)
                                                @case('AFN') افغانی @break
                                                @case('USD') دالر @break
                                                @default {{ $report->currency }}
                                                @endswitch
                                            </span>
                                        </td>

                                        {{-- ستون "تاریخ" --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($report->record_type === 'withdraw')
                                            {{ $report->created_at ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d')
                                            : '-' }}
                                            @else
                                            {{ $report->paid_date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d')
                                            : '-' }}
                                            @endif
                                        </td>

                                        {{-- ستون "توضیحات" --}}
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                            @if($report->record_type === 'withdraw')
                                            {{ $report->description ?? '-' }}
                                            @else
                                            وضعیت: {{ $report->is_reduce ? 'کسر شده' : 'کسر نشده' }}
                                            @endif
                                        </td>
                                        @break

                                        @case('accounting')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-blue-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900">{{ $report->market->name ?? '-'
                                                    }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $report->type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $report->shopkeeper->fullname ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $report->expanses_type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900">{{ number_format($report->price)
                                                }}</span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->paid_date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d')
                                            : '-' }}
                                        </td>

                                        @break


                                        @case('fund')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
        {{ $report->paid > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $report->direction }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $report->expanses_type ?? '-' }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap font-bold {{ $report->paid < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format($report->amount) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                @switch($report->currency)
                                                @case('AFN') افغانی @break
                                                @case('USD') دالر @break
                                                @default {{ $report->currency }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->paid_date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d')
                                            : ($report->created_at ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d')
                                            : '-') }}
                                        </td>
                                        @break
                                        @case('outside')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-green-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900">{{ $report->market->name ?? '-'
                                                    }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                {{ $report->customer_id ? 'bg-purple-100 text-purple-800' : 
                                                ($report->staff_id ? 'bg-orange-100 text-orange-800' : 
                                                ($report->shopkeeper_id ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                                @if($report->customer_id)
                                                مشتری
                                                @elseif($report->staff_id)
                                                کارمند
                                                @elseif($report->shopkeeper_id)
                                                دوکاندار
                                                @else
                                                نامشخص
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $report->customer->fullname ?? $report->staff->fullname ??
                                            $report->shopkeeper->fullname ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900">{{ number_format($report->paid)
                                                    }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                @switch($report->currency)
                                                @case('AFN') افغانی @break
                                                @case('USD') دالر @break
                                                @default {{ $report->currency }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'
                                            }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                            {{ $report->description ?? '-' }}
                                        </td>
                                        @break

                                        @case('salary')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-amber-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900">{{ $report->market->name ?? '-'
                                                    }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $report->staff->fullname ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900">{{ number_format($report->salary)
                                                }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-green-600">{{ number_format($report->paid)
                                                }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-red-600">{{ number_format($report->remained)
                                                }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-purple-600">{{ number_format($report->loan)
                                                }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                @switch($report->currency)
                                                @case('AFN') افغانی @break
                                                @case('USD') دالر @break
                                                @default {{ $report->currency }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->paid_date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d')
                                            : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $report->is_reduce ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $report->is_reduce ? 'فعال' : 'غیرفعال' }}
                                            </span>
                                        </td>
                                        @break

                                        @case('deposit')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-orange-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900">{{
                                                    $report->accounting->market->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $report->accounting->shopkeeper->fullname ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $report->expanses_type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900">{{ number_format($report->price)
                                                }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-green-600">{{ number_format($report->paid)
                                                }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-red-600">{{ number_format($report->remained)
                                                }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                @switch($report->currency)
                                                @case('AFN') افغانی @break
                                                @case('USD') دالر @break
                                                @default {{ $report->currency }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->paid_date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d')
                                            : '-' }}
                                        </td>
                                        @break

                                        @case('loan')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-red-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900">{{ $report->market->name ?? '-'
                                                    }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
            {{ $report->person === 'مشتری' ? 'bg-purple-100 text-purple-800' : 
               ($report->person === 'دوکاندار' ? 'bg-blue-100 text-blue-800' : 
               'bg-orange-100 text-orange-800') }}">
                                                {{ $report->person }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            @if($report->person === 'مشتری' && $report->customer)
                                            {{ $report->customer->fullname }}
                                            @elseif($report->person === 'دوکاندار' && $report->shopkeeper)
                                            {{ $report->shopkeeper->fullname }}
                                            @elseif($report->person === 'کارمند' && $report->staff)
                                            {{ $report->staff->fullname }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900">{{ number_format($report->amount)
                                                }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-green-600">{{
                                                number_format($report->totalPaid()) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="font-bold {{ $report->remainingAmount() > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ number_format($report->remainingAmount()) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                @switch($report->currency)
                                                @case('AFN') افغانی @break
                                                @case('USD') دالر @break
                                                @default {{ $report->currency }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'
                                            }}
                                        </td>
                                        @break

                                        @case('payment')
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            #{{ $report->loan_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900">{{ number_format($report->amount)
                                                }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                @switch($report->currency)
                                                @case('AFN') افغانی @break
                                                @case('USD') دالر @break
                                                @default {{ $report->currency }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'
                                            }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                            {{ $report->description ?? '-' }}
                                        </td>
                                        @break

                                        @case('buy')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-indigo-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900">{{ $report->market->name ?? '-'
                                                    }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $report->customer->fullname ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $report->property }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900">{{ number_format($report->price)
                                                    }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                @switch($report->currency)
                                                @case('AFN') افغانی @break
                                                @case('USD') دالر @break
                                                @default {{ $report->currency }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->created_at ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d')
                                            : '-' }}
                                        </td>
                                        @break

                                        @case('sell')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-teal-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900">{{ $report->market->name ?? '-'
                                                    }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $report->customer->fullname ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                                {{ $report->property }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900">{{ number_format($report->price)
                                                    }}</span>
                                            </div>
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
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                            {{ $report->details ?? '-' }}
                                        </td>
                                        @break

                                        @case('withdraw_log')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                                {{ $report->expanses_type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $report->recipient_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900">{{ number_format($report->amount)
                                                }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                @switch($report->currency)
                                                @case('AFN') افغانی @break
                                                @case('USD') دالر @break
                                                @default {{ $report->currency }}
                                                @endswitch
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
                                                    @if(app()->environment('local'))
                                                    <p class="text-xs text-yellow-600">نوع گزارش: {{ $reportType }}</p>
                                                    @endif
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

                <!-- Export Buttons -->
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