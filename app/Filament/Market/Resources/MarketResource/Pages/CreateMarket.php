<?php

namespace App\Filament\Market\Resources\MarketResource\Pages;

use App\Filament\Market\Resources\MarketResource;
use App\Models\Market\Market;
use App\Models\Market\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Validation\Rule;

class CreateMarket extends CreateRecord
{
    protected static string $resource = MarketResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        parent::mount();

        $currentUser = Auth::user();

        // پیدا کردن آیدی ادمین اصلی (اگر کاربر خودش ادمین باشد، خودش، در غیر این صورت admin_id)
        $adminId = $currentUser->role === 'admin' ? $currentUser->id : $currentUser->admin_id;

        // پیدا کردن خود ادمین برای بررسی محدودیت
        $adminUser = User::find($adminId);
        $marketLimit = $adminUser?->market_limit ?? 1;

        // شمارش کل مارکت‌های ایجاد شده توسط این ادمین
        $marketCount = Market::where('admin_id', $adminId)->count();

        if ($marketCount >= $marketLimit && $currentUser->role !== 'superadmin') {
            Notification::make()
                ->title("شما فقط مجاز به ثبت {$marketLimit} مارکت هستید و نمی‌توانید مارکت جدیدی ثبت کنید.")
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(MarketResource::getUrl('index'));
        }
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:100',
                Rule::unique('markets', 'name'),
            ],
            'location' => ['required', 'max:400'],
            'total_shop' => ['required', 'integer', 'min:1'],
            'floor' => ['required', 'integer', 'min:0'],
            'booth' => ['required', 'in:دارد,ندارد'],
            'booth_number' => ['nullable', 'integer', 'min:0'],
            'stock' => ['required', 'in:دارد,ندارد'],
            'parking' => ['required', 'in:دارد,ندارد'],
            'market_owner' => ['required', 'image'],
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $data['admin_id'] = $user->role === 'admin' ? $user->id : $user->admin_id;
        return $data;
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('مارکت با موفقیت ثبت شد.')
            ->success()
            ->send();
    }
}
