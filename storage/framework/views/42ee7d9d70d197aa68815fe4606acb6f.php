<div class="relative" wire:click.outside="$set('open', false)">

    
    <button
        wire:click="toggle"
        type="button"
        class="relative rounded-full bg-[#DEE8FC] border border-[#2563EB] p-2 hover:bg-[#cdd9f9] transition">

        <img src="<?php echo e(asset('assets/sarafi/all_icon/bill-header.svg')); ?>" class="w-6 h-6">

        <?php if($count): ?>
            <span
                class="absolute -top-1 -right-1 bg-red-600 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center animate-pulse">
                <?php echo e($count); ?>

            </span>
        <?php endif; ?>
    </button>

    
    <?php if($open): ?>
        <div class="absolute left-0 mt-2 w-80 bg-white shadow-xl rounded-xl z-50 overflow-hidden border border-gray-200">

            <div class="flex justify-between items-center px-4 py-2 border-b bg-gray-50">
                <span class="text-sm font-semibold text-gray-700">نوتیفیکیشن‌ها</span>
                <button wire:click="refreshData" class="text-xs text-blue-600 hover:underline">بروزرسانی</button>
            </div>

            <div class="max-h-96 overflow-y-auto">
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition <?php echo e($n->is_read ? 'opacity-70' : 'bg-blue-50'); ?>">

                        
                        <span class="flex-shrink-0 w-3 h-3 mt-1 rounded-full
                            <?php echo e($n->type === 'receive' ? 'bg-green-500' : 'bg-red-500'); ?>">
                        </span>

                        
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800"><?php echo e($n->title); ?></p>
                            <p class="text-xs text-gray-600 truncate"><?php echo e($n->message); ?></p>
                        </div>

                        
                        <span class="text-xs text-gray-400 flex-shrink-0 mt-1">
                            <?php echo e(\Carbon\Carbon::parse($n->created_at)->format('H:i')); ?>

                        </span>

                        
                        <?php if(!$n->is_read): ?>
                            <button wire:click="markAsRead(<?php echo e($n->id); ?>)"
                                    class="text-xs text-blue-600 hover:underline ml-2">
                                خواندن
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-center text-gray-400 py-4">
                        نوتیفیکیشنی وجود ندارد
                    </p>
                <?php endif; ?>
            </div>

            <div class="px-4 py-2 border-t text-center bg-gray-50 text-xs text-gray-500">
                همه نوتیفیکیشن‌ها
            </div>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/bell.blade.php ENDPATH**/ ?>