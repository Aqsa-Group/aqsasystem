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
                            ->required(),

                        Forms\Components\TextInput::make('amount')
                            ->label('مبلغ رسید')
                            ->numeric()
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $currency = $data['currency'];
                        $amount = (float)$data['amount'];

                        $currentBalance = $record->{$currency} ?? 0;
                        $remaining = max($currentBalance - $amount, 0);

                        $record->{$currency} = $remaining;
                        $record->save();

                        
                       $safe = Safe::first(); 
                        if ($safe) {
                            if ($currency === 'AFN') {
                                $safe->AFN = max(($safe->AFN ?? 0) - $amount, 0);
                            } elseif ($currency === 'USD') {
                                $safe->USD = max(($safe->USD ?? 0) - $amount, 0);
                            }
                            $safe->save();
}
                        CompanyPayment::create([
                            'company_id' => $record->id,
                            'currency' => $currency,
                            'total_debt' => $currentBalance,
                            'paid_amount' => $amount,
                            'remaining' => $remaining,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('رسید ثبت شد')
                            ->send();
                    }),
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
