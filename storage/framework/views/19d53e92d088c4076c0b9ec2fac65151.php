<div x-data="liveClock()" x-init="init()" class="flex items-center gap-3 rtl:text-right"
    style="min-width:160px;">
    <div class="text-sm font-medium leading-5">
        <div class="jalali-date text-xs opacity-80">
            <?php echo e(\Morilog\Jalali\Jalalian::now()->format('%A، %d %B %Y')); ?>

        </div>

        <div class="jalali-time text-lg font-semibold" x-text="timeString"></div>
    </div>
</div>

<script>
    function liveClock() {
        return {
            timeString: new Date().toLocaleTimeString('fa-IR'),

            init() {
                setInterval(() => {
                    this.timeString = new Date().toLocaleTimeString('fa-IR');
                }, 1000);
            }
        }
    }
</script>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/widgets/live-clock-widget.blade.php ENDPATH**/ ?>