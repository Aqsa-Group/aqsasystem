<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\SafeResource\Pages\ListSafes;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;

class SafeResource extends Resource
{
    
    protected static ?string $navigationIcon = 'mdi-safe-square-outline';
    protected static ?string $navigationLabel = 'صندوق';
    protected static ?string $navigationGroup = 'بخش مالی';
    protected static ?string $pluralLabel = 'صندوق';

   
    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin' , 'Financial Manager' , 'admin']);
    }

    
    protected static ?int $navigationSort = -2;

    public static function getPages(): array
    {
        return [
            'index' => ListSafes::route('/'),
        ];
    }

    



  

    
}
