<div class="flex items-center gap-4 text-sm vazir">

    <!-- Shamsi -->
    <div class="flex items-center gap-2 px-3 py-1.5
                    bg-gray-100 dark:bg-gray-800
                    rounded-lg shadow-sm">

        <!-- Calendar Icon -->
        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="18" rx="2" />
            <path d="M16 2v4M8 2v4M3 10h18" />
        </svg>

        <span class="text-gray-700 dark:text-gray-200">
            {{ $shamsiDate }}
        </span>

        <span class="text-gray-400 text-xs">
            {{ $shamsiDay }}
        </span>
    </div>

    <!-- Miladi -->
    <div class="flex items-center gap-2 px-3 py-1.5
                    bg-gray-100 dark:bg-gray-800
                    rounded-lg shadow-sm">

        <!-- Globe Icon -->
        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" />
            <path d="M2 12h20M12 2a15 15 0 0 1 0 20M12 2a15 15 0 0 0 0 20" />
        </svg>

        <span class="text-gray-700 dark:text-gray-200">
            {{ $miladiDate }}
        </span>

        <span class="text-gray-400 text-xs">
            {{ $miladiDay }}
        </span>
    </div>

    <!-- Clock -->
    <div class="flex items-center gap-2 px-3 py-1.5
                    bg-gray-100 dark:bg-gray-800
                    rounded-lg shadow-sm">

        <!-- Clock Icon -->
        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 6v6l4 2" />
        </svg>

        <span id="liveClock" class="font-medium font-inter text-gray-700 dark:text-gray-200">
            00:00:00
        </span>

    </div>
    <script>
        function updateClock() { 
    const now = new Date();

    let hours = now.getHours(); 
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    const period = hours >= 12 ? 'بعد از ظهر' : 'قبل از ظهر';

    hours = hours % 12 || 12;

    const time = `${hours}:${minutes}:${seconds} ${period}`;

    document.getElementById('liveClock').textContent = time;
}

setInterval(updateClock, 1000);
updateClock();
    </script>

</div>