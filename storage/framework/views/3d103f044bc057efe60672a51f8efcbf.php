<div class="p-8 min-h-screen font-sans bg-white/15">

    
    <h1 class="text-4xl font-bold text-gray-800 mb-8 pb-4 flex items-center gap-3">
        <i class="fas fa-users-cog text-blue-600"></i> 
                    <?php echo e($editId ? 'ویرایش کاربر' : 'افزودن کاربر'); ?>

            </h2>
    </h1>

    <div class="grid lg:grid-cols-2 gap-8">

        
        <div class="bg-white shadow-xl rounded-3xl p-8 border border-gray-200">
           
            <?php $currentUser = Auth::guard('sarafi')->user(); ?>

            <form wire:submit.prevent="save" class="space-y-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <?php
                        $inputs = [
                            ['model' => 'name', 'placeholder' => 'نام', 'type' => 'text', 'icon' => 'fas fa-user'],
                            [
                                'model' => 'lastname',
                                'placeholder' => 'نام خانوادگی',
                                'type' => 'text',
                                'icon' => 'fas fa-user',
                            ],
                            [
                                'model' => 'sarafi_name',
                                'placeholder' => 'نام صرافی',
                                'type' => 'text',
                                'icon' => 'fas fa-building',
                            ],
                            [
                                'model' => 'address',
                                'placeholder' => 'آدرس',
                                'type' => 'text',
                                'icon' => 'fas fa-map-marker-alt',
                            ],
                            [
                                'model' => 'phone',
                                'placeholder' => 'شماره تلفن',
                                'type' => 'text',
                                'icon' => 'fas fa-phone',
                            ],
                            [
                                'model' => 'username',
                                'placeholder' => 'نام کاربری',
                                'type' => 'text',
                                'icon' => 'fas fa-user-circle',
                            ],
                            [
                                'model' => 'password',
                                'placeholder' => 'رمز عبور',
                                'type' => 'password',
                                'icon' => 'fas fa-lock',
                            ],
                        ];
                    ?>

                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $inputs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $input): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex flex-col">
                            <div class="relative">
                                <i
                                    class="<?php echo e($input['icon']); ?> absolute top-3 right-3 text-gray-400 pointer-events-none"></i>
                                <input wire:model.defer="<?php echo e($input['model']); ?>" type="<?php echo e($input['type']); ?>"
                                    placeholder="<?php echo e($input['placeholder']); ?>"
                                    class="w-full border border-gray-300 rounded-xl px-10 py-3 bg-white focus:ring-2 focus:ring-blue-200 focus:outline-none transition <?php $__errorArgs = [$input['model']];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 ring-red-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = [$input['model']];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-600 text-sm mt-1"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                    
                    <!--[if BLOCK]><![endif]--><?php if($currentUser && $currentUser->role === 'superadmin'): ?>
                        <div class="flex flex-col">
                            <div class="relative">
                                <i class="fas fa-users absolute top-3 right-3 text-gray-400 pointer-events-none"></i>
                                <input wire:model.defer="user_limition" type="number" placeholder="تعداد مجاز کاربران"
                                    class="w-full border border-gray-300 rounded-xl px-10 py-3 bg-white focus:ring-2 focus:ring-blue-200 focus:outline-none transition <?php $__errorArgs = ['user_limition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 ring-red-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['user_limition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-600 text-sm mt-1"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="flex flex-col">
                            <div class="relative">
                                <i
                                    class="fas fa-user-shield absolute top-3 right-3 text-gray-400 pointer-events-none"></i>
                                <select wire:model.defer="role"
                                    class="w-full border rounded-xl px-10 py-3 bg-white appearance-none focus:ring-2 focus:ring-blue-200 focus:outline-none transition <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 ring-red-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">انتخاب نقش</option>
                                    <option value="admin">مدیر</option>
                                </select>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-600 text-sm mt-1"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    <?php elseif($currentUser && $currentUser->role === 'admin'): ?>
                        <div class="flex flex-col">
                            <div class="relative">
                                <i class="fas fa-user-tag absolute top-3 right-3 text-gray-400 pointer-events-none"></i>
                                <select wire:model.defer="role"
                                    class="w-full border border-gray-300 rounded-xl px-10 py-3 bg-white appearance-none focus:ring-2 focus:ring-blue-200 focus:outline-none transition <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 ring-red-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">انتخاب نقش</option>
                                    <option value="warehouse_manager">خرانه دار</option>
                                    <option value="internal_officer">مسوول احواله جات داخلی</option>
                                    <option value="external_officer">مسوول احواله جات خارجی</option>
                                </select>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-600 text-sm mt-1"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                </div>

                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" wire:click="resetInputFields"
                        class="px-6 py-3 bg-gray-300 rounded-xl hover:bg-gray-400 transition">
                        لغو
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition flex items-center gap-2">
                        <i class="fas fa-save"></i> ذخیره
                    </button>
                </div>
            </form>
        </div>

        
       <div class="bg-white shadow-xl rounded-3xl p-4 border border-gray-200">

   <div class="flex justify-between items-center mb-4 flex-wrap gap-3">

    
    <div class="relative flex-1 min-w-[250px]">

        
        <button wire:click="$toggle('filterOpen')"
            class="flex items-center gap-2 px-3 py-2 border rounded bg-gray-100 hover:bg-gray-200 transition">
            <i class="fas fa-filter text-gray-700"></i>
        </button>

        
        <!--[if BLOCK]><![endif]--><?php if($filterOpen): ?>
            <div class="absolute mt-2 left-32 bg-white border rounded-xl shadow-lg p-3 w-64 z-50 flex flex-col gap-2">

                
           
                <select wire:model="filterRole" class="border rounded px-3 py-2 bg-white w-full">
                    <option value="">همه نقش‌ها</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>


                
                <select wire:model="filterSarafi" class="border rounded px-3 py-2 bg-white w-full">
                    <option value="">همه صرافی‌ها</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->sarafis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sarafi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sarafi); ?>"><?php echo e($sarafi); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>

                
                <button wire:click="applyFilter"
                    class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 w-full">
                    اعمال فیلتر
                </button>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    <div class="relative w-1/3 min-w-[200px]">
        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="text" wire:model.debounce.500ms="search" wire:keydown.enter="searchUser"
            placeholder="جستجو..."
            class="border border-gray-300 rounded-xl pl-10 pr-3 py-2 w-full
                   focus:ring-2 focus:ring-blue-200 focus:outline-none transition text-sm">
    </div>

</div>

    
    <div class="overflow-hidden rounded-2xl border border-gray-200">
        <table class="min-w-full bg-white text-sm">
            <thead class="bg-blue-50">
                <tr>
                    <th class="px-3 py-2 text-gray-700 text-left w-8">#</th>
                    <th class="px-3 py-2 text-gray-700 text-left w-20">نام</th>
                    <th class="px-3 py-2 text-gray-700 text-left w-24">نام خانوادگی</th>
                    <th class="px-3 py-2 text-gray-700 text-left w-24">نام کاربری</th>
                    <th class="px-3 py-2 text-gray-700 text-left w-28">نقش</th>
                    <th class="px-3 py-2 text-gray-700 text-left">وضعیت</th>
                    <th class="px-3 py-2 text-gray-700 text-left">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-blue-50 transition cursor-pointer">
                        <td class="px-3 py-2 font-medium text-gray-700"><?php echo e($users->firstItem() + $index); ?></td>
                        <td class="px-3 py-2 text-gray-700"><?php echo e($user->name); ?></td>
                        <td class="px-3 py-2 text-gray-700"><?php echo e($user->lastname); ?></td>
                        <td class="px-3 py-2 text-gray-700"><?php echo e($user->username); ?></td>
                            <td class="px-3 py-2 font-medium text-gray-600">
                                <?php echo e($roles[$user->role] ?? $user->role); ?>

                            </td>
                          <td class="px-3 py-2">
                            <!--[if BLOCK]><![endif]--><?php if($user->status): ?>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full font-semibold">فعال</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full font-semibold">غیرفعال</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>
                        <td class="px-3 py-2 flex gap-2">
                            <button wire:click="edit(<?php echo e($user->id); ?>)"
                                class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                <i class="fas fa-edit"></i> ویرایش
                            </button>
                            <button wire:click="$set('confirmDeleteId', <?php echo e($user->id); ?>)"
                                class="text-red-600 hover:text-red-800 flex items-center gap-1">
                                <i class="fas fa-trash-alt"></i> حذف
                            </button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>
    </div>

    
    <div class="mt-5 flex justify-center">
        <?php echo e($users->links()); ?>

    </div>
</div>




        
        <!--[if BLOCK]><![endif]--><?php if($alert): ?>
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded-3xl shadow-2xl w-96 text-center animate-fadeIn z-50">
                    <h3 class="text-xl font-bold mb-3 text-gray-800"><?php echo e($alert['title']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo e($alert['message']); ?></p>
                    <button wire:click="$set('alert', null)"
                        class="px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                        باشه
                    </button>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($confirmDeleteId): ?>
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded-3xl shadow-2xl w-96 text-center animate-fadeIn z-50">
                    <h3 class="text-xl font-bold mb-4 text-red-600">آیا مطمئن هستید؟</h3>
                    <p class="text-gray-600 mb-6">این عمل قابل بازگشت نیست!</p>
                    <div class="flex justify-center gap-4">
                        <button wire:click="$set('confirmDeleteId', null)"
                            class="px-5 py-2 bg-gray-300 rounded-xl hover:bg-gray-400 transition">خیر</button>
                        <button wire:click="delete"
                            class="px-5 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition flex items-center gap-2">
                            <i class="fas fa-trash-alt"></i> بلی
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    </div>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <?php $__env->stopPush(); ?>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/user-management.blade.php ENDPATH**/ ?>