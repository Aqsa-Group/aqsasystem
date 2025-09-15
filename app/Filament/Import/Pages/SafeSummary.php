<?php

namespace App\Filament\Import\Pages;

use App\Models\Import\Safe;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;


class SafeSummary extends Page
{
    protected static ?string $slug = 'safe-summary';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.import.pages.safe-summary';
    protected static ?string $title = '🏦 گزارش صندوق';
        public function getTitle(): string|Htmlable
    {
        return '';
    }

    public $safeSummary = [];

    public function mount()
    {
        $safe = Safe::latest()->first();

        $this->safeSummary = $safe ? [
            'total' => $safe->total,
            'today' => $safe->today,
            'last_update' => $safe->last_update,
        ] : [
            'total' => 0,
            'today' => 0,
            'last_update' => null,
        ];
    }
}
