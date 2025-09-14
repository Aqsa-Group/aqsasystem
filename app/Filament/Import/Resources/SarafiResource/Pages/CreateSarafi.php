<?php

namespace App\Filament\Import\Resources\SarafiResource\Pages;

use App\Filament\Import\Resources\SarafiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSarafi extends CreateRecord
{
    protected static string $resource = SarafiResource::class;

       public function getTitle(): string
    {
        return 'ثبت صرافی';
    }

}
