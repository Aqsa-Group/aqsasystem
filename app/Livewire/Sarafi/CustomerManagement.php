<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;

class CustomerManagement extends Component
{
    public $showForm = true;

    protected $listeners = [
        'customerSaved' => 'hideForm',
        'cancelEdit' => 'hideForm',
        'scrollToForm' => 'scrollToForm'
    ];

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
    }

    public function hideForm()
    {
        $this->showForm = false;
    }

    public function showForm()
    {
        $this->showForm = true;
    }

    public function scrollToForm()
    {
        $this->showForm = true;
        $this->dispatch('doScroll');
    }

    public function render()
    {
        return view('livewire.sarafi.customer-management');
    }
}
