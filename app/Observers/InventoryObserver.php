<?php

namespace App\Observers;

use App\Models\Import\Inventory;
use App\Notifications\InventoryAvailableNotification;
use Illuminate\Support\Facades\Auth;
 use App\Models\Import\User;


class InventoryObserver
{
    /**
     * Handle the Inventory "created" event.
     */
    public function created(Inventory $inventory): void
    {
        //
    }

    /**
     * Handle the Inventory "updated" event.
     */
public function updated(Inventory $inventory)
{
    if ($inventory->getOriginal('all_exist_number') > 0 && $inventory->all_exist_number == 0) {
        $admins = User::whereIn('role', ['superadmin', 'admin'])->get();

        foreach ($admins as $admin) {
            $admin->notify(new InventoryAvailableNotification($inventory));
        }
    }
}



    /**
     * Handle the Inventory "deleted" event.
     */
    public function deleted(Inventory $inventory): void
    {
        //
    }

    /**
     * Handle the Inventory "restored" event.
     */
    public function restored(Inventory $inventory): void
    {
        //
    }

    /**
     * Handle the Inventory "force deleted" event.
     */
    public function forceDeleted(Inventory $inventory): void
    {
        //
    }
}
