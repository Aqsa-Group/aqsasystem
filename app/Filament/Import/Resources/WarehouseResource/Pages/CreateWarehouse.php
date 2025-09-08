<?php

namespace App\Filament\Import\Resources\WarehouseResource\Pages;

use App\Filament\Import\Resources\WarehouseResource;
use App\Models\Import\Inventory;
use App\Models\Import\Warehouse;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateWarehouse extends CreateRecord
{
    protected static string $resource = WarehouseResource::class;

    public function getTitle(): string
    {
        return 'ثبت جنس';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function create(bool $shouldRedirect = true): void
    {
        $data = $this->form->getState();
        $data = $this->convertFarsiNumbers($data);
    
        $inventory = Inventory::where('barcode', $data['barcode'])
        ->where('user_id', Auth::id())
        ->first();
    
        if (!$inventory) {
            Notification::make()
                ->title('خطا: جنس در گدام یافت نشد!')
                ->danger()
                ->persistent()
                ->send();
            return;
        }
    
        $unit = $data['unit'] ?? 'دانه';
        $reducedQuantity = 0;
        $reducedAllExistNumber = 0;
    
        if (in_array($unit, ['بسته', 'کارتن'])) {
            $reducedQuantity = (int) ($data['quantity'] ?? 0);
            $reducedBigQuantity = (int) ($data['big_quantity'] ?? 1);
            $reducedAllExistNumber = $reducedQuantity * $reducedBigQuantity;
        } else {
            $reducedAllExistNumber = (int) ($data['all_exist_number'] ?? 0);
        }
    
        if ($inventory->all_exist_number < $reducedAllExistNumber) {
            Notification::make()
                ->title('خطا: موجودی گدام کافی نیست!')
                ->body("موجودی فعلی: {$inventory->all_exist_number} - مقدار درخواستی: {$reducedAllExistNumber}")
                ->danger()
                ->persistent()
                ->send();
            return;
        }
    
        if (in_array($unit, ['بسته', 'کارتن'])) {
            $inventory->quantity -= $reducedQuantity;
        }
        $inventory->all_exist_number -= $reducedAllExistNumber;
        $inventory->save();
    
        $existing = Warehouse::where('barcode', $data['barcode'])->first();
    
        if ($existing) {
            if (in_array($unit, ['بسته', 'کارتن'])) {
                $existing->quantity += $reducedQuantity;
            }
            $existing->all_exist_number += $reducedAllExistNumber;
            $existing->total_price += (float) ($data['total_price'] ?? 0);
            $existing->save();
    
            Notification::make()
                ->title('جنس به دوکان اضافه شد و از گدام کم شد')
                ->body("مقدار {$reducedAllExistNumber} عدد از گدام منتقل شد.")
                ->success()
                ->persistent()
                ->actions([
                    Action::make('مشاهده کالا')
                        ->url(WarehouseResource::getUrl('edit', ['record' => $existing->id]))
                        ->openUrlInNewTab()
                        ->button(),
                ])
                ->send();
    
            if ($shouldRedirect) {
                $this->redirect(WarehouseResource::getUrl('edit', ['record' => $existing->id]));
            }
            return;
        }
    
        $this->form->fill($data);
        parent::create(false);
    
        Notification::make()
            ->title('جنس جدید به دوکان اضافه شد و از گدام کم شد')
            ->body("مقدار {$reducedAllExistNumber} عدد از گدام منتقل شد.")
            ->success()
            ->persistent()
            ->send();
    
        if ($shouldRedirect) {
            $this->redirect(WarehouseResource::getUrl('index'));
        }
    }
    
    

    /**
     * تبدیل اعداد فارسی به انگلیسی در داده‌های فرم
     */
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
