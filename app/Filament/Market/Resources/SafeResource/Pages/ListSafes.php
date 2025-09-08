<?php

namespace App\Filament\Market\Resources\SafeResource\Pages;

use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ListSafes extends Page
{
    protected static string $resource = \App\Filament\Market\Resources\SafeResource::class;

    protected static string $view = 'filament.resources.safe-resource.pages.list-safes';

    public function getTitle(): string
    {
        return 'لیست موجودی صندوق';
    }

    public $rows = [];

    public function mount()
    {
        $user = Auth::user();

        if (!$user) {
            $this->rows = [];
            return;
        }

        if ($user->role === 'superadmin') {
            $data = DB::connection('market')->table('accountings')
                ->select('expanses_type', 'currency', DB::raw('SUM(paid) as total_paid'))
                ->groupBy('expanses_type', 'currency')
                ->get();
        } else {
            $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;
        
            $data = DB::connection('market')->table('accountings')
                ->select('expanses_type', 'currency', DB::raw('SUM(paid) as total_paid'))
                ->where('admin_id', $adminId)
                ->groupBy('expanses_type', 'currency')
                ->get();
        }
        

        $grouped = $data->groupBy('expanses_type');

        foreach ($grouped as $type => $group) {
            $this->rows[] = [
                'type' => $type,
                'af' => $group->firstWhere('currency', 'AFN')?->total_paid ?? 0,
                'us' => $group->firstWhere('currency', 'USD')?->total_paid ?? 0,
                'er' => $group->firstWhere('currency', 'EUR')?->total_paid ?? 0,
                'ir' => $group->firstWhere('currency', 'IRR')?->total_paid ?? 0,
            ];
        }
    }
}
