<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;
use Morilog\Jalali\Jalalian;

class ExchangeRate extends Component
{
        public $date;

    public function render()
    {
        return view('livewire.sarafi.exchange-rate');
    }

    public function mount(){
               $this->date = Jalalian::now()->format('Y/m/d');
    }
}
