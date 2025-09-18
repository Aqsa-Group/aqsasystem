<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Exchange;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ExchangesReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-switch-horizontal';
    protected static ?string $navigationLabel = 'گزارش تبدیل ارز';
    protected static ?string $navigationGroup = 'گزارشات و تنظیمات';
    protected static ?string $title = '💱 گزارش تبدیل ارز';
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.import.pages.exchanges-reports';



       public function getTitle(): string|Htmlable
    {
        return '';
    }



    public $type = '';
    public $user_name = '';
    public $exchanges = [];

    public function updated()
    {
        $this->loadExchanges();
    }

    public function mount()
    {
        $this->loadExchanges();
    }

    public function loadExchanges()
    {
        $query = Exchange::query()->with(['sarafi', 'customer', 'staff']);

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->user_name) {
            $query->where(function($q) {
                $q->where('person', 'like', "%{$this->user_name}%")
                  ->orWhereHas('sarafi', fn($q2) => $q2->where('name', 'like', "%{$this->user_name}%"))
                  ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$this->user_name}%"))
                  ->orWhereHas('staff', fn($q2) => $q2->where('name', 'like', "%{$this->user_name}%"));
            });
        }

        $this->exchanges = $query->latest()->get()->toArray();
    }
}
