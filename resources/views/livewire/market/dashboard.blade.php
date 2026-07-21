<div>
    {{-- هدر --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">داشبورد مارکت</h1>
        <div class="ml-2 mb-4 font-bold">
            <livewire:market.date />
        </div>

    </div>

   <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-6 gap-3 mb-8">
    @php
        $quickLinks = [
             ['name' => 'برداشت از صندوق', 'icon' => 'arrow-down', 'url' => '/market/withdrawals'],
            ['name' => 'حسابداری', 'icon' => 'calculator', 'url' => '/market/accountings'],
            ['name' => 'تسویه نشده', 'icon' => 'hourglass-half', 'url' => '/market/deposits'],
            ['name' => 'ثبت عواید بیرونی', 'icon' => 'hand-holding-usd', 'url' => '/market/outsides'],
            ['name' => 'پرداخت معاشات', 'icon' => 'money-check-alt', 'url' => '/market/salaries'],
            ['name' => 'مارکت‌ها', 'icon' => 'store', 'url' => '/market/markets'],
            ['name' => 'دوکان‌ها', 'icon' => 'store-alt', 'url' => '/market/shops'],
            ['name' => 'غرفه‌ها', 'icon' => 'cubes', 'url' => '/market/booths'],
            ['name' => 'دوکانداران', 'icon' => 'user-tie', 'url' => '/market/shopkeepers'],
            ['name' => 'مشتریان', 'icon' => 'users', 'url' => '/market/customers'],
           
            ['name' => 'گزارش گیری عمومی', 'icon' => 'file-alt', 'url' => '/market/general-reports'],
            ['name' => 'گزارش رسید دوکان‌ها', 'icon' => 'clipboard-list', 'url' => '/market/deposit-logs'],
        ];
    @endphp

    @foreach($quickLinks as $link)
        <a href="{{ $link['url'] }}" class="block min-w-0">
            <div class="border bg-white dark:bg-gray-800 dark:border-gray-700 rounded-xl py-3 px-3 flex items-center justify-center gap-2 text-[#184D6C] dark:text-white hover:bg-[#184D6C] hover:text-white transition-all shadow-md h-full">
                <i class="fas fa-{{ $link['icon'] }} text-lg flex-shrink-0"></i>
                <span class="text-sm truncate max-w-[80px] md:max-w-[100px]">{{ $link['name'] }}</span>
            </div>
        </a>
    @endforeach
</div>
    {{-- تب‌ها با دیزاین جدید --}}
    <div x-data="{ activeTab: 'general' }" class="mt-6">
        <div class="flex flex-wrap gap-3 border-b border-gray-200 dark:border-gray-700 pb-4">
            <!-- TAB: عمومی -->
            <a href="#" @click.prevent="activeTab = 'general'"
                class="flex items-center gap-3 px-6 py-2 rounded-2xl transition-all duration-300 bg-white dark:bg-gray-800 border dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white"
                :class="activeTab === 'general' ? 'text-black dark:text-white dark:bg-gray-700 border-[#184D6C] dark:border-white tab-active-shadow' : 'border-transparent'">
                <div class="h-9 w-9 flex items-center justify-center rounded-full">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M22 8.52V3.98C22 2.57 21.36 2 19.77 2H15.73C14.14 2 13.5 2.57 13.5 3.98V8.51C13.5 9.93 14.14 10.49 15.73 10.49H19.77C21.36 10.5 22 9.93 22 8.52Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M22 19.77V15.73C22 14.14 21.36 13.5 19.77 13.5H15.73C14.14 13.5 13.5 14.14 13.5 15.73V19.77C13.5 21.36 14.14 22 15.73 22H19.77C21.36 22 22 21.36 22 19.77Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M10.5 8.52V3.98C10.5 2.57 9.86 2 8.27 2H4.23C2.64 2 2 2.57 2 3.98V8.51C2 9.93 2.64 10.49 4.23 10.49H8.27C9.86 10.5 10.5 9.93 10.5 8.52Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M10.5 19.77V15.73C10.5 14.14 9.86 13.5 8.27 13.5H4.23C2.64 13.5 2 14.14 2 15.73V19.77C2 21.36 2.64 22 4.23 22H8.27C9.86 22 10.5 21.36 10.5 19.77Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <span class="font-bold">عمومی</span>
            </a>

            <!-- TAB: صندوق -->
            <a href="#" @click.prevent="activeTab = 'cash'"
                class="flex items-center gap-3 px-6 py-2 rounded-2xl transition-all duration-300 bg-white dark:bg-gray-800 border dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white"
                :class="activeTab === 'cash' ? 'text-black dark:text-white dark:bg-gray-700 border-[#184D6C] dark:border-white tab-active-shadow' : 'border-transparent'">
                <div class="h-9 w-9 flex items-center justify-center rounded-full">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.5 12C13.5 12.8284 12.8284 13.5 12 13.5C11.1716 13.5 10.5 12.8284 10.5 12C10.5 11.1716 11.1716 10.5 12 10.5C12.8284 10.5 13.5 11.1716 13.5 12Z"
                            fill="#1C274C" />
                        <path d="M12 12V8" stroke="currentColor" stroke-width="1.5" />
                        <path d="M12 12L15.5 13.5" stroke="currentColor" stroke-width="1.5" />
                        <path d="M12 12L8.5 13.5" stroke="currentColor" stroke-width="1.5" />
                        <path d="M4.5 7V10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M4.5 14V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path
                            d="M22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C21.5093 4.43821 21.8356 5.80655 21.9449 8"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path
                            d="M12 5C8.70017 5 7.05025 5 6.02513 6.02513C5 7.05025 5 8.70017 5 12C5 15.2998 5 16.9497 6.02513 17.9749C7.05025 19 8.70017 19 12 19C15.2998 19 16.9497 19 17.9749 17.9749C19 16.9497 19 15.2998 19 12C19 8.70017 19 7.05025 17.9749 6.02513C17.2933 5.34351 16.3354 5.11511 14.8 5.03857"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path
                            d="M10 8.53513C10.5883 8.19479 11.2714 8 12 8C14.2091 8 16 9.79086 16 12C16 14.2091 14.2091 16 12 16C9.79086 16 8 14.2091 8 12C8 11.6547 8.04375 11.3196 8.12602 11"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </div>
                <span class="font-bold">صندوق</span>
            </a>

            <!-- TAB: برداشت‌ها -->
            <a href="#" @click.prevent="activeTab = 'withdrawals'"
                class="flex items-center gap-3 px-6 py-2 rounded-2xl transition-all duration-300 bg-white dark:bg-gray-800 border dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white"
                :class="activeTab === 'withdrawals' ? 'text-black dark:text-white dark:bg-gray-700 border-[#184D6C] dark:border-white tab-active-shadow' : 'border-transparent'">
                <div class="h-9 w-9 flex items-center justify-center rounded-full">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.4002 17.42H10.8902C9.25016 17.42 7.92016 16.04 7.92016 14.34C7.92016 13.93 8.26016 13.59 8.67016 13.59C9.08016 13.59 9.42016 13.93 9.42016 14.34C9.42016 15.21 10.0802 15.92 10.8902 15.92H13.4002C14.0502 15.92 14.5902 15.34 14.5902 14.64C14.5902 13.77 14.2802 13.6 13.7702 13.42L9.74016 12C8.96016 11.73 7.91016 11.15 7.91016 9.36C7.91016 7.82 9.12016 6.58 10.6002 6.58H13.1102C14.7502 6.58 16.0802 7.96 16.0802 9.66C16.0802 10.07 15.7402 10.41 15.3302 10.41C14.9202 10.41 14.5802 10.07 14.5802 9.66C14.5802 8.79 13.9202 8.08 13.1102 8.08H10.6002C9.95016 8.08 9.41016 8.66 9.41016 9.36C9.41016 10.23 9.72016 10.4 10.2302 10.58L14.2602 12C15.0402 12.27 16.0902 12.85 16.0902 14.64C16.0802 16.17 14.8802 17.42 13.4002 17.42Z"
                            fill="currentColor" />
                        <path
                            d="M12 18.75C11.59 18.75 11.25 18.41 11.25 18V6C11.25 5.59 11.59 5.25 12 5.25C12.41 5.25 12.75 5.59 12.75 6V18C12.75 18.41 12.41 18.75 12 18.75Z"
                            fill="currentColor" />
                        <path
                            d="M12 22.75C6.07 22.75 1.25 17.93 1.25 12C1.25 6.07 6.07 1.25 12 1.25C17.93 1.25 22.75 6.07 22.75 12C22.75 17.93 17.93 22.75 12 22.75ZM12 2.75C6.9 2.75 2.75 6.9 2.75 12C2.75 17.1 6.9 21.25 12 21.25C17.1 21.25 21.25 17.1 21.25 12C21.25 6.9 17.1 2.75 12 2.75Z"
                            fill="currentColor" />
                    </svg>
                </div>
                <span class="font-bold">برداشت‌ها</span>
            </a>
        </div>

        {{-- محتوای تب‌ها --}}
        <div class="mt-6">
            {{-- تب عمومی (۸ کارت) --}}
            <div x-show="activeTab === 'general'" x-cloak>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- ۴ کارت اول: آمار کلی -->
                    <div
                        class="flex flex-col justify-between rounded-2xl bg-[#184D6C] shadow-md p-3 min-h-[160px] text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center justify-center bg-white/20 rounded-full h-12 w-12">
                                <i class="fas fa-store-alt text-xl"></i>
                            </div>
                            <p class="text-sm font-medium opacity-80">کل دکان‌ها</p>
                        </div>
                        <div class="mt-2 text-right">
                            <p class="text-2xl font-extrabold">{{ $allshop ?? 0 }}</p>
                            <p class="text-xs opacity-70">آخرین بروزرسانی: {{ now()->format('H:i') }}</p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col justify-between rounded-2xl bg-[#184D6C] shadow-md p-3 min-h-[160px] text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center justify-center bg-white/20 rounded-full h-12 w-12">
                                <i class="fas fa-cubes text-xl"></i>
                            </div>
                            <p class="text-sm font-medium opacity-80">کل غرفه ها</p>
                        </div>
                        <div class="mt-2 text-right">
                            <p class="text-2xl font-extrabold">{{ $allbooth ?? 0 }}</p>
                            <p class="text-xs opacity-70">آخرین بروزرسانی: {{ now()->format('H:i') }}</p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col justify-between rounded-2xl bg-[#184D6C] shadow-md p-3 min-h-[160px] text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center justify-center bg-white/20 rounded-full h-12 w-12">
                                <i class="fas fa-store text-xl"></i>
                            </div>
                            <p class="text-sm font-medium opacity-80">دکان‌های خالی</p>
                        </div>
                        <div class="mt-2 text-right">
                            <p class="text-2xl font-extrabold">{{ $emptyShop ?? 0 }}</p>
                            <p class="text-xs opacity-70">آخرین بروزرسانی: {{ now()->format('H:i') }}</p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col justify-between rounded-2xl bg-[#184D6C] shadow-md p-3 min-h-[160px] text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center justify-center bg-white/20 rounded-full h-12 w-12">
                                <i class="fas fa-cube text-xl"></i>
                            </div>
                            <p class="text-sm font-medium opacity-80">غرفه های خالی</p>
                        </div>
                        <div class="mt-2 text-right">
                            <p class="text-2xl font-extrabold">{{ $emptyBooth ?? 0 }}</p>
                            <p class="text-xs opacity-70">آخرین بروزرسانی: {{ now()->format('H:i') }}</p>
                        </div>
                    </div>

                    <!-- ۴ کارت دوم: جزئیات دکان‌ها -->
                    <div
                        class="flex flex-col justify-between rounded-2xl bg-[#184D6C] shadow-md p-3 min-h-[160px] text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center justify-center bg-white/20 rounded-full h-12 w-12">
                                <i class="fas fa-handshake text-xl"></i>
                            </div>
                            <p class="text-sm font-medium opacity-80">سرقفلی</p>
                        </div>
                        <div class="mt-2 text-right">
                            <p class="text-2xl font-extrabold">{{ $sarqofli ?? 0 }}</p>
                            <p class="text-xs opacity-70">تعداد</p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col justify-between rounded-2xl bg-[#184D6C] shadow-md p-3 min-h-[160px] text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center justify-center bg-white/20 rounded-full h-12 w-12">
                                <i class="fas fa-home text-xl"></i>
                            </div>
                            <p class="text-sm font-medium opacity-80">کرایه‌ای</p>
                        </div>
                        <div class="mt-2 text-right">
                            <p class="text-2xl font-extrabold">{{ $rent ?? 0 }}</p>
                            <p class="text-xs opacity-70">تعداد</p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col justify-between rounded-2xl bg-[#184D6C] shadow-md p-3 min-h-[160px] text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center justify-center bg-white/20 rounded-full h-12 w-12">
                                <i class="fas fa-lock text-xl"></i>
                            </div>
                            <p class="text-sm font-medium opacity-80">گروی</p>
                        </div>
                        <div class="mt-2 text-right">
                            <p class="text-2xl font-extrabold">{{ $qerawi ?? 0 }}</p>
                            <p class="text-xs opacity-70">تعداد</p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col justify-between rounded-2xl bg-[#184D6C] shadow-md p-3 min-h-[160px] text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center justify-center bg-white/20 rounded-full h-12 w-12">
                                <i class="fas fa-archive text-xl"></i>
                            </div>
                            <p class="text-sm font-medium opacity-80">کل دکان‌ها</p>
                        </div>
                        <div class="mt-2 text-right">
                            <p class="text-2xl font-extrabold">{{ $allshop ?? 0 }}</p>
                            <p class="text-xs opacity-70">مجموع</p>
                        </div>
                    </div>
                </div>
            </div>

          {{-- تب صندوق (۴ کارت) --}}
<div x-show="activeTab === 'cash'" x-cloak>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="flex flex-col justify-between rounded-2xl bg-[#184D6C] shadow-md p-4 min-h-[220px] text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-center bg-white/20 rounded-full h-14 w-14">
                    <i class="fas fa-money-bill text-2xl"></i>
                </div>
                <p  dir="ltr" class="text-sm font-medium opacity-80">موجودی افغانی</p>
            </div>
            <div class="mt-3 text-right">
                <p  dir="ltr" class="text-3xl font-extrabold">{{ number_format($cashCards['AFN'] ?? 0) }}</p>
                <p class="text-xs opacity-70">آخرین بروزرسانی: {{ now()->format('H:i') }}</p>
            </div>
        </div>

        <div class="flex flex-col justify-between rounded-2xl bg-[#184D6C] shadow-md p-4 min-h-[220px] text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-center bg-white/20 rounded-full h-14 w-14">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
                <p class="text-sm font-medium opacity-80">موجودی دالر</p>
            </div>
            <div class="mt-3 text-right">
                <p dir="ltr" class="text-3xl font-extrabold">{{ number_format($cashCards['USD'] ?? 0) }}</p>
                <p class="text-xs opacity-70">آخرین بروزرسانی: {{ now()->format('H:i') }}</p>
            </div>
        </div>

        <div class="flex flex-col justify-between rounded-2xl bg-[#184D6C] shadow-md p-4 min-h-[220px] text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-center bg-white/20 rounded-full h-14 w-14">
                    <i class="fas fa-euro-sign text-2xl"></i>
                </div>
                <p class="text-sm font-medium opacity-80">موجودی یورو</p>
            </div>
            <div class="mt-3 text-right">
                <p  dir="ltr" class="text-3xl font-extrabold">{{ number_format($cashCards['EUR'] ?? 0) }}</p>
                <p class="text-xs opacity-70">آخرین بروزرسانی: {{ now()->format('H:i') }}</p>
            </div>
        </div>

        <div class="flex flex-col justify-between rounded-2xl bg-[#184D6C] shadow-md p-4 min-h-[220px] text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-center bg-white/20 rounded-full h-14 w-14">
                    <i class="fas fa-rial text-2xl"></i>
                </div>
                <p class="text-sm font-medium opacity-80">موجودی تومان</p>
            </div>
            <div class="mt-3 text-right">
                <p class="text-3xl font-extrabold">{{ number_format($cashCards['IRR'] ?? 0) }}</p>
                <p class="text-xs opacity-70">آخرین بروزرسانی: {{ now()->format('H:i') }}</p>
            </div>
        </div>
    </div>
</div>

        {{-- تب برداشت‌ها --}}
<div x-show="activeTab === 'withdrawals'" x-cloak>

    @php
        $meta = [
            'AFN' => ['label' => 'برداشت افغانی', 'icon' => 'money-bill-wave'],
            'USD' => ['label' => 'برداشت دالر', 'icon' => 'dollar-sign'],
            'EUR' => ['label' => 'برداشت یورو', 'icon' => 'euro-sign'],
            'IRR' => ['label' => 'برداشت تومان', 'icon' => 'money-check-alt'],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        @foreach($withdrawCards as $currency => $amount)

            @php $m = $meta[$currency] ?? null; @endphp

            @if($m)
            <div class="flex flex-col justify-between rounded-2xl bg-[#184D6C] shadow-md p-4 min-h-[220px] text-white">

                <div class="flex items-center justify-between">
                    <div class="flex items-center justify-center bg-white/20 rounded-full h-14 w-14">
                        <i class="fas fa-{{ $m['icon'] }} text-2xl"></i>
                    </div>

                    <p class="text-sm font-medium opacity-80">
                        {{ $m['label'] }}
                    </p>
                </div>

                <div class="mt-3 text-right">
                    <p class="text-3xl font-extrabold">
                        {{ number_format($amount) }}
                    </p>
                    <p class="text-xs opacity-70">امروز</p>
                </div>

            </div>
            @endif

        @endforeach

    </div>
</div>
        </div>
    </div>

    {{-- استایل کمکی برای tab-active-shadow --}}
    <style>
        .tab-active-shadow {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: #e5e7eb;
        }

        .dark .tab-active-shadow {
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.05);
            border-color: #374151;
        }
    </style>
    {{-- استایل کمکی برای tab-active-shadow --}}
    <style>
        .tab-active-shadow {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: #e5e7eb;
        }

        .dark .tab-active-shadow {
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.05);
            border-color: #374151;
        }
    </style>

    {{-- اسکریپت‌ها و استایل‌ها (بدون تغییر) --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function createSimpleDatePicker(inputId) {
                const input = document.getElementById(inputId);
                if (!input) return;
                input.addEventListener('click', function() {
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
                    const days = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];
                    days.forEach(day => {
                        calendarHTML += `<div style="text-align: center; font-weight: bold;">${day}</div>`;
                    });
                    const daysInMonth = new persianDate([year, month, 1]).daysInMonth();
                    for (let day = 1; day <= daysInMonth; day++) {
                        calendarHTML += `
                            <div style="text-align: center; padding: 5px; cursor: pointer; border-radius: 4px;" 
                                 onclick="selectDate('${inputId}', ${year}, ${month + 1}, ${day})">
                                ${day}
                            </div>`;
                    }
                    calendarHTML += `</div></div>`;
                    const existingCalendar = document.getElementById('simpleCalendar');
                    if (existingCalendar) existingCalendar.remove();
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
            window.selectDate = function(inputId, year, month, day) {
                const dateString = `${year}/${month.toString().padStart(2, '0')}/${day.toString().padStart(2, '0')}`;
                document.getElementById(inputId).value = dateString;
                if (inputId === 'startDatePicker') {
                    @this.set('startDateJalali', dateString);
                } else if (inputId === 'endDatePicker') {
                    @this.set('endDateJalali', dateString);
                }
                const calendar = document.getElementById('simpleCalendar');
                if (calendar) calendar.remove();
            };
            createSimpleDatePicker('startDatePicker');
            createSimpleDatePicker('endDatePicker');
        });
    </script>
    @endpush

    @push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        /* فونت‌ها و تنظیمات دیگر (همانند قبل) */
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