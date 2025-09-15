<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Import\Inventory;

class InventoryAvailableNotification extends Notification
{
    use Queueable;

    protected $inventory;
    protected $type;

    public function __construct(Inventory $inventory, $type = 'available')
    {
        $this->inventory = $inventory;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

 public function toDatabase($notifiable)
{
    // فقط حالت out_of_stock
    $message = "موجودی جنس «{$this->inventory->name}» در گدام  صفر شد!";

    return [
        'title' => $message,
        'inventory_id' => $this->inventory->id,
        'format' => 'filament',
        'type' => 'out_of_stock',
    ];
}


}

