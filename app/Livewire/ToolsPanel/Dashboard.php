<?php

namespace App\Livewire\ToolsPanel;

use Livewire\Component;

class Dashboard extends Component
{


    public array $months;
    public array $profitPerMonth;
    public array $lossPerMonth;

    // کارت‌ها
    public int $salesToday;
    public int $profitToday;
    public int $lossToday;
    public int $customersTotal;
    public int $transactionsToday;
    public int $balanceTotal;

    public function mount()
    {
        // ماه‌های فارسی (حمل..حوت)
        $this->months = ['حمل','ثور','جوزا','سرطان','اسد','سنبله','میزان','عقرب','قوس','جدی','دلو','حوت'];

        // تولید داده‌های نمونه — هر mount مقدار جدید می‌سازد (می‌توانید از DB جایگزین کنید)
        // برای طبیعی‌تر شدن: تولید پایه و سپس اندکی تصادفی اضافه می‌کنیم
        $baseProfit = [1200, 1800, 2500, 1900, 2700, 3100, 3400, 3000, 3200, 3600, 4000, 4200];
        $baseLoss   = [800, 600, 1100, 900, 1200, 700, 500, 800, 750, 900, 850, 700];

        $this->profitPerMonth = array_map(function($v){ return $v + rand(-200, 600); }, $baseProfit);
        $this->lossPerMonth   = array_map(function($v){ return $v + rand(-150, 400); }, $baseLoss);

        // کارت‌ها — تولید اعداد تصادفی معقول
        $this->salesToday = rand(80000, 160000);
        $this->profitToday = rand(5000, 45000);
        $this->lossToday = rand(0, 15000);
        $this->customersTotal = rand(80, 240);
        $this->transactionsToday = rand(10, 120);
        $this->balanceTotal = rand(2000000, 9000000);
    }
        
    public function render()
    {
        return view('livewire.tools-panel.dashboard');
    }
}
