<div class="w-full mx-auto p-4 md:p-6 space-y-6">
    <!-- نمایش پیام موفقیت -->
    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-400 bg-[#2B65E5] vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e(session('message')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- نمایش پیام خطا -->
    <?php if(session()->has('error')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] dark:bg-gradient-to-b dark:from-red-500 dark:to-red-400 bg-red-600 vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e(session('error')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- عنوان و توضیحات -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white tracking-tight">مدیریت نسخه پشتیبان دیتابیس</h1>
        <p class="mt-3 text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
            تهیه و بازیابی نسخه پشتیبان از تمام اطلاعات حساب کاربری شما
        </p>
    </div>

    <!-- کارت اصلی -->
    <div class="                        bg-white shadow-sm backdrop-blur-2xl border border-[#D7E5EC]
 dark:from-gray-800 dark:to-gray-900 rounded-2xl  dark:border-gray-700 overflow-hidden">
      
        <!-- بخش محتوای کارت -->
        <div class="p-6 space-y-6">
            <!-- کارت‌های عملیات -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- کارت Export -->
                <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 ml-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">تهیه نسخه پشتیبان</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
                                دریافت فایل SQL از تمام اطلاعات حساب کاربری
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button 
                            wire:click="backup"
                            wire:loading.attr="disabled"
                            class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            
                            <span wire:loading.remove class="flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                دریافت بک‌آپ SQL
                            </span>
                            
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                در حال پردازش...
                            </span>
                        </button>
                        
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                            ⚡ بسته به حجم دیتابیس، این عملیات ممکن است چند ثانیه طول بکشد
                        </p>
                    </div>
                </div>

                <!-- کارت Import -->
                <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 ml-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">بازیابی اطلاعات</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
                                آپلود فایل SQL برای بازیابی اطلاعات
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="relative">
                            <input 
                                type="file" 
                                wire:model="sqlFile"
                                accept=".sql,.txt"
                                class="hidden"
                                id="sqlFileInput"
                                wire:loading.attr="disabled">
                            
                            <label for="sqlFileInput" class="block w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 border-2 border-dashed border-blue-300 dark:border-blue-700 hover:border-blue-400 dark:hover:border-blue-600 rounded-lg cursor-pointer transition-colors duration-200">
                                <div class="flex flex-col items-center justify-center">
                                    <!--[if BLOCK]><![endif]--><?php if(!$sqlFile): ?>
                                        <svg class="w-8 h-8 text-blue-500 dark:text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                        </svg>
                                        <span class="text-blue-600 dark:text-blue-400 font-medium">انتخاب فایل SQL</span>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs mt-1">حداکثر حجم: 10MB</span>
                                    <?php else: ?>
                                        <svg class="w-8 h-8 text-green-500 dark:text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-green-600 dark:text-green-400 font-medium"><?php echo e($sqlFile->getClientOriginalName()); ?></span>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs mt-1"><?php echo e(round($sqlFile->getSize() / 1024, 2)); ?> KB</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </label>
                        </div>
                        
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['sqlFile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="mt-2 text-red-600 dark:text-red-400 text-sm">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        
                        <!--[if BLOCK]><![endif]--><?php if($sqlFile): ?>
                            <div class="mt-3 space-y-2">
                                <button 
                                    wire:click="import"
                                    wire:loading.attr="disabled"
                                    class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                    
                                    <span wire:loading.remove class="flex items-center">
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                        </svg>
                                        شروع بازیابی اطلاعات
                                    </span>
                                    
                                    <span wire:loading class="flex items-center">
                                        <svg class="animate-spin w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        در حال پردازش...
                                    </span>
                                </button>
                                
                                <button 
                                    wire:click="$set('sqlFile', null)"
                                    wire:loading.attr="disabled"
                                    class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200">
                                    حذف فایل
                                </button>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                            ⚠️ این عملیات داده‌های فعلی را با اطلاعات فایل جایگزین می‌کند
                        </p>
                    </div>
                </div>
            </div>

            <!-- اطلاعات بیشتر -->
            <div class="bg-gradient-to-r from-blue-50/70 to-blue-100/30 dark:from-blue-900/20 dark:to-blue-800/10 p-5 rounded-xl border border-blue-200 dark:border-blue-800">
                <div class="flex">
                    <div class="flex-shrink-0 ml-3">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-800 dark:text-blue-300">نکات مهم</h3>
                        <ul class="mt-2 space-y-2">
                            <li class="flex items-start">
                                <span class="text-blue-500 dark:text-blue-400 ml-2 mt-1">•</span>
                                <span class="text-gray-700 dark:text-gray-300">قبل از بازیابی، حتماً از داده‌های فعلی نسخه پشتیبان تهیه کنید</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-500 dark:text-blue-400 ml-2 mt-1">•</span>
                                <span class="text-gray-700 dark:text-gray-300">فایل باید از طریق همین سیستم تهیه شده باشد</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-500 dark:text-blue-400 ml-2 mt-1">•</span>
                                <span class="text-gray-700 dark:text-gray-300">در صورت خطا، عملیات متوقف می‌شود</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال Import -->
<!--[if BLOCK]><![endif]--><?php if($showImportModal): ?>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" x-data x-show="$wire.showImportModal" x-transition>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full">
            <!-- هدر مودال -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center ml-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">بازیابی اطلاعات</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1"><?php echo e($importStatus); ?></p>
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php if($importProgress === 100): ?>
                        <button wire:click="closeImportModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            <!-- بدنه مودال -->
            <div class="p-6">
                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">پیشرفت عملیات</span>
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400"><?php echo e($importProgress); ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div 
                            class="h-2.5 rounded-full transition-all duration-300 <?php echo e($importProgress === 100 ? 'bg-green-500' : 'bg-gradient-to-r from-blue-500 to-indigo-600'); ?>"
                            style="width: <?php echo e($importProgress); ?>%">
                        </div>
                    </div>
                </div>

                <!-- وضعیت فعلی -->
                <div class="mb-6">
                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <!--[if BLOCK]><![endif]--><?php if($importProgress < 100): ?>
                            <svg class="animate-spin w-5 h-5 text-blue-500 ml-3" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        <?php else: ?>
                            <!--[if BLOCK]><![endif]--><?php if(str_contains($importStatus, 'موفق')): ?>
                                <svg class="w-5 h-5 text-green-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5 text-red-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <span class="text-gray-700 dark:text-gray-300 mr-2"><?php echo e($importStatus); ?></span>
                    </div>
                </div>

                <!-- دکمه‌ها -->
                <div class="flex justify-end">
                    <!--[if BLOCK]><![endif]--><?php if($importProgress === 100): ?>
                        <button 
                            wire:click="closeImportModal"
                            class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-lg transition-all duration-200">
                            بستن
                        </button>
                    <?php else: ?>
                        <button 
                            wire:click="closeImportModal"
                            class="px-6 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200">
                            لغو عملیات
                        </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php
        $__scriptKey = '1003712764-0';
        ob_start();
    ?>
<script>
    // مدیریت رویدادهای Import
    document.addEventListener('livewire:initialized', () => {
        // رویداد import-success
        Livewire.on('import-success', () => {
            setTimeout(() => {
                // خود session flash پیام را نمایش می‌دهد
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }, 1000);
        });

        // رویداد refresh-page
        Livewire.on('refresh-page', () => {
            setTimeout(() => {
                window.location.reload();
            }, 500);
        });
    });
</script>
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/database-backup.blade.php ENDPATH**/ ?>