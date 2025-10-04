<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use Livewire\Component;

class Dashboard extends Component
{
    public $activeTab = 'general';

    public function render()
    {
         $customerCount = Customer::count();
        return view('livewire.sarafi.dashboard' , compact('customerCount'));
    }



   
}
