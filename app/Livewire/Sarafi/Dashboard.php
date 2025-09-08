<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.sarafi.dashboard');
    }


    public $activeTab = 'general'; 

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

   
}
