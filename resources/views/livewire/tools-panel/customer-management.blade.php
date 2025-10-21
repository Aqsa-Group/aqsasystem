<div>
    <!-- دکمه برای نمایش/پنهان کردن فرم -->
    <div class="mb-4">
        <button 
            wire:click="toggleForm" 
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
            {{ $showForm ? '❌ بستن فرم' : '➕ افزودن مشتری جدید' }}
        </button>
    </div>

    <!-- نمایش فرم -->
    @if($showForm)
    <div id="customer-form-section" class="mb-8">
        <livewire:sarafi.customers />
    </div>
    @endif

    <!-- نمایش جدول -->
    <livewire:sarafi.customers-table />

    <!-- اسکریپت برای مدیریت اسکرول -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('doScroll', () => {
                setTimeout(() => {
                    const formSection = document.getElementById('customer-form-section');
                    if (formSection) {
                        formSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }, 100);
            });
        });
    </script>
</div>