<?php

namespace App\Observers;

use App\Models\Import\Warehouse;
use App\Models\Import\User;
use App\Notifications\InventoryLowNotification;

class WarehouseObserver
{
    public function updated(Warehouse $warehouse)
    {
        $threshold = 5;

        if ($warehouse->all_exist_number < $threshold && $warehouse->getOriginal('all_exist_number') >= $threshold) {
            $admins = User::whereIn('role', ['superadmin', 'admin'])->get();

            foreach ($admins as $admin) {
                $admin->notify(new InventoryLowNotification($warehouse));
            }
        }
    }
}
