<div>
    <div class="w-full mx-auto p-4 bg-white dark:bg-gray-900 rounded-xl shadow-lg grid md:grid-cols-2 gap-4">

    <!-- Right Column: Form -->
    <div class="p-4 md:p-4 flex flex-col justify-center">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white text-center">افزودن/ویرایش کاربر</h2>

        <form wire:submit.prevent="saveCustomer">

            <!-- Profile -->
            <div class="flex flex-col items-center pb-3 relative">
                <label class="mb-2 text-gray-700 dark:text-gray-300 font-medium">عکس مشتری</label>
                <div class="relative w-32 h-32">
                    <?php if($profile): ?>
                        <img src="<?php echo e($profile instanceof \Livewire\TemporaryUploadedFile ? $profile->temporaryUrl() : asset('storage/' . $profile)); ?>" 
                             alt="Profile" class="w-32 h-32 rounded-full object-cover border border-gray-300 dark:border-gray-600">
                    <?php else: ?>
                        <img src="https://i.pravatar.cc/150?img=12" alt="Profile"
                             class="w-32 h-32 rounded-full object-cover border border-gray-300 dark:border-gray-600">
                    <?php endif; ?>
                    <input type="file" wire:model="profile" accept="image/*"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="absolute bottom-0 right-0 bg-blue-600 p-2 rounded-full text-white shadow-md cursor-pointer">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                </div>
                <?php $__errorArgs = ['profile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid grid-cols-2 gap-4">

                <!-- Name -->
                <div class="relative">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">نام کامل</label>
                    <input type="text" wire:model="name" placeholder="نام مشتری"
                           class="w-full pr-10 p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <img src="<?php echo e(asset('assets/sarafi/people.png')); ?>" alt="" class="h-5 w-5 mt-6">
                    </div>
                </div>

                <!-- Account Number -->
                <div class="relative">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">شماره حساب</label>
                    <input  type="text" wire:model.lazy="account" placeholder="شماره حساب مشتری"
                           class="w-full pr-10 p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                    <?php $__errorArgs = ['account'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <img src="<?php echo e(asset('assets/sarafi/number-blocks.png')); ?>" alt="" class="h-5 w-5 mt-6">
                    </div>
                </div>

                <!-- Category -->
                <div class="relative">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">دسته</label>
                    <select wire:model="category" class="w-full pr-10 p-2 rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                        <option>مشتری عادی</option>
                        <option>مشتریان ثابت</option>
                        <option>مشتری طلایی</option>
                        <option>ویژه</option>
                    </select>
                    <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <img src="<?php echo e(asset('assets/sarafi/select.png')); ?>" alt="" class="h-5 w-5 mt-6">
                    </div>
                </div>

                <!-- City -->
                <div class="relative">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">شهر</label>
                    <input type="text" wire:model="city" placeholder="نام شهر مشتری"
                           class="w-full pr-10 p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                    <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <img src="<?php echo e(asset('assets/sarafi/stroke.png')); ?>" alt="" class="h-5 w-5 mt-6">
                    </div>
                </div>

                <!-- Phone -->
                <div class="relative">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">شماره تلفن</label>
                    <input type="text" wire:model.lazy="phone" placeholder="شماره تلفن مشتری"
                           class="w-full pr-10 p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <img src="<?php echo e(asset('assets/sarafi/call.png')); ?>" alt="" class="h-5 w-5 mt-6">
                    </div>
                </div>

                <!-- Tazkira -->
                <div class="relative">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">شماره تذکره</label>
                    <input type="text" wire:model.lazy="tazkira" placeholder="شماره تذکره مشتری"
                           class="w-full pr-10 p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                    <?php $__errorArgs = ['tazkira'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <img src="<?php echo e(asset('assets/sarafi/id-card.png')); ?>" alt="" class="h-5 w-5 mt-6">
                    </div>
                </div>

                <!-- WhatsApp -->
                <div class="relative w-full">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">شماره واتساپ</label>
                    <input type="text" wire:model.lazy="whatsapp" placeholder="شماره واتساپ مشتری"
                           class="w-full pr-10 p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                    <?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <img src="<?php echo e(asset('assets/sarafi/whatsapp.png')); ?>" alt="" class="h-5 w-5 mt-6">
                    </div>
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-center space-x-3 gap-2 rtl:space-x-reverse mt-4">
                <button type="reset" class="flex items-center justify-center gap-2 px-32 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    <i class="fas fa-times mr-2"></i> لغو
                </button>

                <button type="submit" class="flex items-center justify-center gap-2 px-32 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i> ذخیره
                </button>
            </div>

            <!-- Success message -->
            <?php if(session()->has('message')): ?>
                <div class="mt-4 text-green-600 text-center font-semibold">
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?>

        </form>

    </div>

    <!-- Left Column: Custom Image -->
    <div class="relative flex items-center justify-center">
        <img src="<?php echo e(asset('assets/sarafi/customer_bg.jpg')); ?>" alt="Custom Image" class="w-full h-full object-cover rounded-lg shadow-md">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center rounded-lg p-6">
            <p class="text-white text-center text-lg md:text-2xl font-semibold">
                این بخش مربوط به ثبت نام مشتریان می‌باشد. لذا در هنگام ثبت نام، شماره <span class="text-green-400 font-bold">واتساپ</span> مشتری را حتماً وارد نمایید،
                تا کلیه اطلاع‌رسانی‌ها و پیام‌های مربوط به تراکنش‌های صرافی به‌صورت مستقیم برای مشتری ارسال گردد.
            </p>
        </div>
    </div>

<!-- Success Modal -->
<?php if($showSuccessModal): ?>
<div 
    class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
    wire:click.self="$set('showSuccessModal', false)"
>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-96">
        <h2 class="text-xl font-bold text-green-600 mb-4 text-center">موفقعیت</h2>
        <p class="text-gray-700 dark:text-gray-200">
            <?php echo e($successMessage); ?>

        </p>

        <div class="mt-6 flex justify-end">
            <button 
                wire:click="$set('showSuccessModal', false)" 
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
            >
                بستن
            </button>
        </div>
    </div>
</div>
<?php endif; ?>


</div>


</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/customers.blade.php ENDPATH**/ ?>