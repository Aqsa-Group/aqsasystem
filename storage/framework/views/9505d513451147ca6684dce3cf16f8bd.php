<div>
    <?php if(session()->has('success')): ?>
        <div class="bg-green-200 text-green-800 p-2 rounded mb-2">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-4">
        <input type="text" wire:model.debounce.500ms="search" placeholder="جستجو..." class="border rounded px-2 py-1">
        <button wire:click="openCreateModal" class="bg-green-600 text-white px-4 py-2 rounded">افزودن کاربر</button>
    </div>

    <table class="min-w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th>#</th>
                <th>نام</th>
                <th>نام خانوادگی</th>
                <th>نام کاربری</th>
                <th>نقش</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $this->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50">
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($user->name); ?></td>
                    <td><?php echo e($user->lastname); ?></td>
                    <td><?php echo e($user->username); ?></td>
                    <td><?php echo e($user->role); ?></td>
                    <td class="flex gap-2">
                        <button wire:click="edit(<?php echo e($user->id); ?>)" class="text-blue-600">ویرایش</button>
                        <button wire:click="delete(<?php echo e($user->id); ?>)" onclick="return confirm('آیا مطمئن هستید؟')" class="text-red-600">حذف</button>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="mt-4">
        <?php echo e($this->users->links()); ?>

    </div>

    
    <?php if($modalOpen): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg w-96">
                <h2 class="text-lg font-bold mb-4"><?php echo e($editId ? 'ویرایش کاربر' : 'افزودن کاربر'); ?></h2>

                <form wire:submit.prevent="save" class="space-y-3">
                    <input wire:model="name" type="text" placeholder="نام" class="w-full border px-2 py-1 rounded">
                    <input wire:model="lastname" type="text" placeholder="نام خانوادگی" class="w-full border px-2 py-1 rounded">
                    <input wire:model="sarafi_name" type="text" placeholder="نام صرافی" class="w-full border px-2 py-1 rounded">
                    <input wire:model="address" type="text" placeholder="آدرس" class="w-full border px-2 py-1 rounded">
                    <input wire:model="phone" type="text" placeholder="شماره تلفن" class="w-full border px-2 py-1 rounded">
                    <input wire:model="username" type="text" placeholder="نام کاربری" class="w-full border px-2 py-1 rounded">
                    <input wire:model="password" type="password" placeholder="رمز عبور" class="w-full border px-2 py-1 rounded">
                    <select wire:model="role" class="w-full border px-2 py-1 rounded">
                        <option value="">انتخاب نقش</option>
                        <option value="admin">مدیر</option>
                        <option value="user">کاربر</option>
                    </select>

                    <div class="flex justify-end gap-2 mt-2">
                        <button type="button" wire:click="$set('modalOpen', false)" class="px-4 py-2 bg-gray-300 rounded">لغو</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">ذخیره</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/user-form.blade.php ENDPATH**/ ?>