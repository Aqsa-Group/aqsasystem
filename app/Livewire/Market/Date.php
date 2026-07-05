<?php

namespace App\Livewire\Market;

use Livewire\Component;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class Date extends Component
{
    public $shamsiDate;
    public $shamsiDay;

    public $miladiDate;
    public $miladiDay;

    public function mount()
    {
        $now = Carbon::now();

        // شمسی
        $jalali = Jalalian::fromCarbon($now);
        $this->shamsiDate = $jalali->format('Y/m/j');

        // میلادی
        $this->miladiDate = $now->format('Y/m/d');

        $locale = app()->getLocale();

        if ($locale === 'en') {
            $this->shamsiDay = $this->getEnglishDay($now);
            $this->miladiDay = $now->format('l');
        } else {
            $this->shamsiDay = $jalali->format('%A');
            $this->miladiDay = $this->getPersianDay($now);
        }
    }

    private function getEnglishDay($date)
    {
        return $date->format('l');
    }

    private function getPersianDay($date)
    {
        $days = [
            'Saturday' => 'شنبه',
            'Sunday' => 'یکشنبه',
            'Monday' => 'دوشنبه',
            'Tuesday' => 'سه‌شنبه',
            'Wednesday' => 'چهارشنبه',
            'Thursday' => 'پنج‌شنبه',
            'Friday' => 'جمعه',
        ];

        return $days[$date->format('l')] ?? '';
    }

    public function render()
    {
        return view('livewire.market.date');
    }
}