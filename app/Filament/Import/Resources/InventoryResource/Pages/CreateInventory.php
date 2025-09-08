<?php

namespace App\Filament\Import\Resources\InventoryResource\Pages;

use App\Filament\Import\Resources\InventoryResource;
use App\Models\Import\Inventory;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateInventory extends CreateRecord
{
    protected static string $resource = InventoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function create(bool $shouldRedirect = true): void
    {
        $data = $this->form->getState();
        $data = $this->convertFarsiNumbers($data);
    
        $existing = Inventory::where('barcode', $data['barcode'])
        ->first();
    
        if ($existing) {
            $unit = $data['unit'];
    
            if ($unit === 'دانه') {
                $existing->all_exist_number += $data['all_exist_number'];
                $existing->total_price += $data['total_price'];
            } else {
                $addedQuantity = $data['quantity'];
                $addedBigQuantity = $data['big_quantity'] ?? 0;
    
                $existing->quantity += $addedQuantity;
                $existing->all_exist_number += ($addedQuantity * $addedBigQuantity);
                $existing->total_price += $data['total_price'];
            }
    
            $existing->save();
    
            Notification::make()
                ->title('موجودی به‌روزرسانی شد')
                ->success()
                ->persistent()
                ->actions([
                    Action::make('مشاهده کالا')
                        ->url(InventoryResource::getUrl('edit', ['record' => $existing->id]))
                        ->openUrlInNewTab()
                        ->button(),
                ])
                ->send();
    
            $this->redirect(InventoryResource::getUrl('edit', ['record' => $existing->id]));
            return;
        }
    
        $this->form->fill($data);
        parent::create($shouldRedirect);
    }
    

    private function convertFarsiNumbers(array $data): array
    {
        $farsiDigits = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $englishDigits = ['0','1','2','3','4','5','6','7','8','9'];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = str_replace($farsiDigits, $englishDigits, $value);
            }
        }

        return $data;
    }
}
