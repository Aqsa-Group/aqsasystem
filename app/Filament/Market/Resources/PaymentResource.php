<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\PaymentResource\Pages;
use App\Models\Market\Payment;
use App\Models\Market\Loan;
use App\Models\Market\Market;
use App\Models\Market\Customer;
use App\Models\Market\Shopkeeper;
use App\Models\Market\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'majestic-money-plus-line';
    protected static ?string $navigationGroup = "بخش مالی";
    protected static ?string $navigationLabel = "رسید";
    protected static ?string $modelLabel = "رسید";
    protected static ?string $pluralModelLabel = "رسیدها";


    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'Financial Manager', 'admin']);
    }


    public static function form(Form $form): Form
    {
        $user = Auth::user();

        return $form->schema([
            Forms\Components\Select::make('loan_id')
                ->label('قرضه مرتبط')
                ->options(function () use ($user) {
                    return Loan::with(['customer', 'shopkeeper', 'staff'])
                        ->when($user->role === 'admin', fn($q) => $q->where('admin_id', $user->id))
                        ->when($user->role !== 'superadmin' && $user->role !== 'admin', fn($q) => $q->where('admin_id', $user->admin_id))
                        ->get()
                        ->mapWithKeys(function ($loan) {
                            $person = match (true) {
                                $loan->person === 'مشتری' && $loan->customer?->fullname => $loan->customer->fullname,
                                $loan->person === 'دوکاندار' && $loan->shopkeeper?->fullname => $loan->shopkeeper->fullname,
                                $loan->person === 'کارمند' && $loan->staff?->fullname => $loan->staff->fullname,
                                default => 'نامشخص',
                            };

                            $remaining = number_format($loan->remainingAmount());
                            return [$loan->id => "{$person} -باقیمانده قرض: {$remaining}"];
                        });
                })
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $loan = \App\Models\Market\Loan::with(['customer', 'shopkeeper', 'staff'])->find($state);

                    // Reset all first
                    $set('customer_id', null);
                    $set('shopkeeper_id', null);
                    $set('staff_id', null);

                    if ($loan) {
                        if ($loan->person === 'مشتری' && $loan->customer) {
                            $set('customer_id', $loan->customer->id);
                        } elseif ($loan->person === 'دوکاندار' && $loan->shopkeeper) {
                            $set('shopkeeper_id', $loan->shopkeeper->id);
                        } elseif ($loan->person === 'کارمند' && $loan->staff) {
                            $set('staff_id', $loan->staff->id);
                        }
                    }
                }),


            Forms\Components\Hidden::make('customer_id'),
            Forms\Components\Hidden::make('shopkeeper_id'),
            Forms\Components\Hidden::make('staff_id'),


            Forms\Components\Select::make('currency')
                ->label('ارز')
                ->options([
                    'AFN' => 'افغانی',
                    'USD' => 'دالر',
                    'EUR' => 'یورو',
                    'IRR' => 'تومان',
                ])
                ->required(),

            Forms\Components\TextInput::make('amount')
                ->label('مقدار پرداخت')
                ->numeric()
                ->required(),

            Forms\Components\DatePicker::make('date')
                ->label('تاریخ رسید')
                ->jalali()
                ->required(),

            Forms\Components\Textarea::make('description')
                ->label('توضیحات')
                ->rows(3)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('loan.id')->label('کد قرضه'),
                Tables\Columns\TextColumn::make('amount')->label('مبلغ پرداخت')->numeric(),
                Tables\Columns\TextColumn::make('currency')->label('واحد پول'),
                Tables\Columns\TextColumn::make('date')
                    ->label('تاریخ')
                    ->formatStateUsing(
                        fn($state) =>
                        Jalalian::fromDateTime($state)->format('Y/m/d') . ' - ' . date('g:i A', strtotime($state))
                    ),
                Tables\Columns\TextColumn::make('description')->label('توضیحات'),
                Tables\Columns\TextColumn::make('created_at')->label('تاریخ ثبت')->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('printContract')
                    ->label('چاپ رسید')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('amount.print', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        if ($user->role === 'superadmin') {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id);
    }
}
