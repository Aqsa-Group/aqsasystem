    <?php
        use Morilog\Jalali\Jalalian;
        $currentUser = Auth::guard('sarafi')->user();
    ?>

    <div class="w-full max-w-[460px] md:max-w-[800px] lg:max-w-[1200px] p-8 mx-auto bg-white border border-[#D7E5EC] shadow-sm rounded-2xl space-y-6">

        
        <!--[if BLOCK]><![endif]--><?php if($currentUser && $currentUser->role === 'superadmin'): ?>
            <div class="text-center space-y-2">
                <h2 class="text-3xl font-extrabold text-gray-900 vazir tracking-widest">ثبت اطلاعیه آنلاین</h2>
                <p class="text-lg text-gray-600 vazir">پیام جدید برای کاربران صرافی ثبت کنید</p>
            </div>

            <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
                <p class="text-green-600 text-sm text-center"><?php echo e(session('success')); ?></p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <form wire:submit.prevent="save" class="space-y-4">
                <div class="relative">
                    <textarea wire:model="message" placeholder="متن پیام خود را وارد کنید..."
                            class="w-full p-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 text-sm resize-none h-32"
                            maxlength="500"></textarea>
                    <div class="absolute bottom-2 right-3 text-xs text-gray-400">
                        <?php echo e(strlen($message)); ?>/500
                    </div>
                </div>

                <div class="flex justify-center gap-4 mt-4">
                    <button type="button" wire:click="$set('message','')"
                            class="flex-1 py-3 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400 transition">
                        لغو
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 bg-[#184D6C] text-white rounded-xl hover:bg-[#0f3750] transition">
                        ثبت پیام
                    </button>
                </div>
            </form>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <div class="space-y-4 mt-6">
            <h3 class="text-2xl font-bold text-gray-700 vazir text-center mb-6">آخرین اطلاعیه‌ها</h3>

    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $isNew = $currentUser && !$n->seenByUsers->contains($currentUser->id);

            $messageContent = $n->message;

            // فقط برای سوپر ادمین
            if($currentUser && $currentUser->role === 'superadmin') {
                // [highlight]...[/highlight] → آبی و بولد
                $messageContent = preg_replace('/\[highlight\](.*?)\[\/highlight\]/u', '<span class="text-blue-600 font-bold">$1</span>', $messageContent);

                // [big]...[/big] → بزرگتر
                $messageContent = preg_replace('/\[big\](.*?)\[\/big\]/u', '<span class="text-xl font-bold">$1</span>', $messageContent);
            }
        ?>

        <div class="p-4 border rounded-xl shadow-sm transition hover:shadow-md hover:scale-[1.01] bg-white flex justify-between items-start gap-4
                    <?php echo e($isNew ? 'border-blue-400 bg-blue-50' : 'border-gray-200 bg-gray-50'); ?>">
            <div>
                <p class="text-gray-800 text-sm vazir break-words"><?php echo $messageContent; ?></p>
                <div class="flex items-center gap-1 mt-2 text-xs text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                    </svg>
                    <?php echo e(Jalalian::fromCarbon(\Carbon\Carbon::parse($n->created_at))->format('Y/m/d h:i A')); ?>

                    <!--[if BLOCK]><![endif]--><?php if($isNew): ?>
                        <span class="ml-2 px-1.5 py-0.5 text-[10px] font-bold text-white bg-blue-500 rounded">جدید</span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-center text-gray-400 text-sm">هیچ اطلاعیه‌ای وجود ندارد</p>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>
    </div>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/online-notif.blade.php ENDPATH**/ ?>