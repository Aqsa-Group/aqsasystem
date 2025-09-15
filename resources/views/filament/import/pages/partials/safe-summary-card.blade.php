<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-6 border">
        <h3 class="text-gray-600 dark:text-gray-300">💰 موجودی کل</h3>
        <p class="text-2xl font-bold text-blue-600 mt-2">
            {{ number_format($safeSummary['total'] ?? 0) }}
        </p>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-6 border">
        <h3 class="text-gray-600 dark:text-gray-300">📅 موجودی امروز</h3>
        <p class="text-2xl font-bold text-green-600 mt-2">
            {{ number_format($safeSummary['today'] ?? 0) }}
        </p>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-6 border">
        <h3 class="text-gray-600 dark:text-gray-300">⏱ آخرین بروزرسانی</h3>
        <p class="text-lg font-semibold mt-2">
            {{ $safeSummary['last_update'] 
                ? \Morilog\Jalali\Jalalian::fromDateTime($safeSummary['last_update'])->format('Y/m/d H:i') 
                : '---' }}
        </p>
    </div>
</div>
