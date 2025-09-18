<?php

namespace App\Filament\Import\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\Import\AddSafe;
use Illuminate\Contracts\Support\Htmlable;


class AddSafeReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.import.pages.add-safe-report';
    protected static bool $shouldRegisterNavigation = false;


           public function getTitle(): string|Htmlable
    {
        return '';
    }


    public $records = [];
    public $currency = '';
    public $description = '';
    public $date = '';

    public function updated($propertyName)
    {
        $this->loadRecords();
    }

    public function mount()
    {
        $this->loadRecords();
    }

    public function loadRecords()
    {
        $query = AddSafe::query();

        if ($this->currency) {
            $query->where('currency', $this->currency);
        }

        if ($this->description) {
            $query->where('description', 'like', '%' . $this->description . '%');
        }

        if ($this->date) {
            $query->whereDate('created_at', $this->date);
        }

        $this->records = $query->orderBy('created_at', 'desc')->get()->toArray();
    }
}
