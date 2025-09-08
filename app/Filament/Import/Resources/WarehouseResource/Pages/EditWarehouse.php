<?php

namespace App\Filament\Import\Resources\WarehouseResource\Pages;

use App\Filament\Import\Resources\WarehouseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWarehouse extends EditRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $fieldsToConvert = ['price', 'quantity'];

        foreach ($fieldsToConvert as $field) {
            if (isset($data[$field])) {
                $data[$field] = $this->convertFarsiNumbersToEnglish($data[$field]);
            }
        }

        return $data;
    }

    private function convertFarsiNumbersToEnglish(string $input): string
    {
        $farsiDigits = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $englishDigits = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($farsiDigits, $englishDigits, $input);
    }
}
