<x-filament-panels::page>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">📊  گزارشات</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('filament.import.pages.sales-reports') }}"
               class="block bg-white dark:bg-gray-900 rounded-2xl shadow p-6 border hover:shadow-lg transition">
                <h3 class="text-lg font-semibold">🛒 گزارش فروش</h3>
                <p class="text-sm text-gray-500 mt-2">مشاهده جزئیات فروش‌ها</p>
            </a>

            <a href="{{ route('filament.import.pages.loans-reports') }}"
               class="block bg-white dark:bg-gray-900 rounded-2xl shadow p-6 border hover:shadow-lg transition">
                <h3 class="text-lg font-semibold">💳 قرضه‌ها</h3>
                <p class="text-sm text-gray-500 mt-2">گزارش وام‌ها</p>
            </a>

            <a href="{{ route('filament.import.pages.withdrawals-reports') }}"
               class="block bg-white dark:bg-gray-900 rounded-2xl shadow p-6 border hover:shadow-lg transition">
                <h3 class="text-lg font-semibold">💸 برداشت‌ها</h3>
                <p class="text-sm text-gray-500 mt-2">گزارش برداشت‌ها</p>
            </a>

            <a href="{{ route('filament.import.pages.safe-summary') }}"
               class="block bg-white dark:bg-gray-900 rounded-2xl shadow p-6 border hover:shadow-lg transition">
                <h3 class="text-lg font-semibold">🏦 صندوق</h3>
                <p class="text-sm text-gray-500 mt-2">خلاصه صندوق</p>
            </a>
        </div>
    </div>
</x-filament-panels::page>
