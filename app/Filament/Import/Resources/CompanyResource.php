<?php

namespace App\Filament\Import\Resources;

use App\Filament\Import\Resources\CompanyResource\Pages;
use App\Models\Import\Company;
use App\Models\Import\Safe;
use App\Models\Import\CompanyPayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'ionicon-business-sharp';
    protected static ?string $navigationLabel = 'حساب شرکت ها';
    protected static ?string $pluralModelLabel = 'شرکت';
    protected static ?string $modelLabel = 'شرکت';

    protected static ?string $navigationGroup = 'بخش خرید و فروش';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('نام')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('address')
                    ->label('آدرس')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label('شماره تلفن')
                    ->tel()
                    ->required()
                    ->maxLength(255),

                Forms\Components\Hidden::make('AFN')
                    ->label('مقدار افغانی')
                    ->default(0),

                Forms\Components\Hidden::make('USD')
                    ->label('مقدار دالر')
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('نام')->searchable(),
                Tables\Columns\TextColumn::make('address')->label('آدرس')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('شماره تلفن')->searchable(),
                Tables\Columns\TextColumn::make('AFN')->label('افغانی')->sortable(),
                Tables\Columns\TextColumn::make('USD')->label('دالر')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('تاریخ ایجاد')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label('تاریخ بروزرسانی')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()->label('نمایش'),
                Tables\Actions\EditAction::make()->label('ویرایش'),

                Action::make('ثبت رسید')
                    ->button()
                    ->label('💰 ثبت رسید')
                    ->modalHeading('ثبت رسید برای شرکت')
                    ->modalWidth('md')
                    ->form([
                        Forms\Components\Select::make('currency')
                            ->label('انتخاب ارز')
                            ->options([
                                'AFN' => 'افغانی',
                                'USD' => 'دالر',
                            ])
                            ->required()
                            ->reactive(),

                        Forms\Components\TextInput::make('amount')
                            ->label('مبلغ رسید')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                    ])
                    ->action(function ($record, array $data) {
                        $currency = $data['currency'];
                        $amount = (float)$data['amount'];

                        $safe = Safe::first();
                        if (!$safe) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('خطا در سیستم صندوق')
                                ->body('صندوق یافت نشد!')
                                ->send();
                            return;
                        }

                        $currentSafeBalance = $safe->{$currency} ?? 0;

                        if ($currentSafeBalance < $amount) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('موجودی ناکافی')
                                ->body('موجودی صندوق برای ارز ' . $currency . ' کافی نیست. موجودی فعلی: ' . number_format($currentSafeBalance))
                                ->send();
                            return;
                        }

                        // محاسبه مانده بدهی شرکت
                        $currentBalance = $record->{$currency} ?? 0;

                        $actualPayment = min($amount, $currentBalance);
                        $remaining = max($currentBalance - $actualPayment, 0);

                        $record->{$currency} = $remaining;
                        $record->save();

                        $safe->{$currency} = max($currentSafeBalance - $actualPayment, 0);
                        $safe->save();

                        CompanyPayment::create([
                            'company_id' => $record->id,
                            'currency' => $currency,
                            'total_debt' => $currentBalance,
                            'paid_amount' => $actualPayment,
                            'remaining' => $remaining,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('رسید ثبت شد')
                            ->body('مبلغ ' . number_format($actualPayment) . ' ' . $currency . ' با موفقیت پرداخت شد.')
                            ->send();
                    })
                    ->modalDescription(
                        fn($record) =>
                        "موجودی صندوق: \n" .
                            "افغانی: " . number_format(Safe::first()->AFN ?? 0) . "\n" .
                            "دالر: " . number_format(Safe::first()->USD ?? 0)
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف گروهی'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'view' => Pages\ViewCompany::route('/{record}'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
