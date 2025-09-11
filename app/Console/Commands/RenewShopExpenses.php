<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Expense;
use App\Models\Market\Accounting;
use App\Models\Market\Shop;
use Carbon\Carbon;

class RenewShopExpenses extends Command
{
    protected $signature = 'expenses:renew';
    protected $description = 'Renew expired shop expenses automatically';

    public function handle()
    {
        $today = Carbon::today();

        // تمام رکوردهای منقضی شده تا امروز
        $expired = Accounting::whereDate('expiration_date', '<=', $today)->get();

        foreach ($expired as $item) {
            $shop = Shop::find($item->shop_id);

            if (! $shop) {
                $this->warn("❌ Shop with ID {$item->shop_id} not found, skipping...");
                continue;
            }

            $newExpiration = Carbon::parse($item->expiration_date)->addMonth();

            Accounting::create([
                'shop_id'        => $item->shop_id,
                'booth_id'       => $item->booth_id,
                'market_id'      => $item->market_id,
                'shopkeeper_id'  => $item->shopkeeper_id,
                'admin_id'       => $item->admin_id,
                'type'           => $item->type,
                'expanses_type'  => $item->expanses_type,
                'meter_serial'   => $item->meter_serial,
                'past_degree'    => $item->past_degree,
                'current_degree' => $item->current_degree,
                'degree_price'   => $item->degree_price,
                'cleared'        => 0,
                'price'          => $shop->price, 
                'currency'       => $item->currency,
                'paid'           => null,
                'remained'       => null,
                'paid_date'      => null,
                'expiration_date'=> $newExpiration,
            ]);

            $this->info("✅ Expense renewed for shop_id {$item->shop_id}, new expiration: {$newExpiration}");
        }

        $this->info('🎉 All expired shop expenses renewed successfully.');
    }
}
