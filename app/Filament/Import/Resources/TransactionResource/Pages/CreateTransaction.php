<?php

namespace App\Filament\Import\Resources\TransactionResource\Pages;

use App\Filament\Import\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
}
