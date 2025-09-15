<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Import\Warehouse;

class InventoryLowNotification extends Notification
{
    use Queueable;

    protected $warehouse;

    public function __construct(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

public function toDatabase($notifiable)
{
    $warehouseName = $this->warehouse->name; 
    $remaining = $this->warehouse->all_exist_number;
    $message = "موجودی جنس «{$this->warehouse->name}» در گدام «{$warehouseName}» در حال تمام شدن است. تعداد باقی‌مانده: {$remaining}";

    return [
        'title' => $message,
        'warehouse_id' => $this->warehouse->id,
        'remaining' => $remaining,
        'format' => 'filament',
        'type' => 'low_stock',
    ];
}


}
