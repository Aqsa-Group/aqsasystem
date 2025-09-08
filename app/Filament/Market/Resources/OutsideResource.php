<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\OutsideResource\Pages;
use App\Models\Market\Customer;
use App\Models\Market\Market;
use App\Models\Market\Outside;
use App\Models\Market\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class OutsideResource extends Resource
{
    protected static ?string $model = Outside::class;

    protected static ?string $navigationIcon = 'iconsax-lin-money-recive';
    protected static ?string $navigationLabel = 'ثبت عواید بیرونی';
    protected static ?string $modelLabel = 'عواید بیرونی';
    protected static ?string $navigationGroup = "بخش مالی";
    protected static ?string $pluralModelLabel = 'عواید بیرونی';

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'Financial Manager', 'admin']);
    }



    public static function form(Form $form): Form
    {
        $user = Auth::user();

        return $form->schema([
            Forms\Components\Select::make('market_id')
                ->label('نام مارکت')
                ->options(
                    Market::query()
                        ->when($user->role === 'admin', fn($q) => $q->where('admin_id', $user->id))
                        ->when($user->role !== 'superadmin' && $user->role !== 'admin', fn($q) => $q->where('admin_id', $user->admin_id))
                        ->pluck('name', 'id')
                )
                ->reactive()
                ->required(),


            Forms\Components\Select::make('person_type')
                ->label('نوع شخص')
                ->options([
                    'customer' => 'مشتری',
                    'staff'    => 'کارمند',
                ])
                ->dehydrated(false)
                ->reactive(),

            Forms\Components\Select::make('customer_id')
                ->label('نام مشتری')
                ->options(function (callable $get) use ($user) {
                    $marketId = $get('market_id');
                    if (!$marketId) {
                        return [];
                    }
                    return Customer::where('market_id', $marketId)
                        ->when($user->role === 'admin', fn($q) => $q->where('admin_id', $user->id))
                        ->when($user->role !== 'superadmin' && $user->role !== 'admin', fn($q) => $q->where('admin_id', $user->admin_id))
                        ->pluck('fullname', 'id');
                })
                ->searchable()
                ->visible(fn(callable $get) => $get('person_type') === 'customer'),

            Forms\Components\Select::make('staff_id')
                ->label('نام کارمند')
                ->options(function (callable $get) use ($user) {
                    $marketId = $get('market_id');
                    if (!$marketId) {
                        return [];
                    }
                    return Staff::where('market_id', $marketId)
                        ->when($user->role === 'admin', fn($q) => $q->where('admin_id', $user->id))
                        ->when($user->role !== 'superadmin' && $user->role !== 'admin', fn($q) => $q->where('admin_id', $user->admin_id))
                        ->pluck('fullname', 'id');
                })
                ->searchable()
                ->visible(fn(callable $get) => $get('person_type') === 'staff'),


            Forms\Components\Hidden::make('type')->default('بیرونی'),

            Forms\Components\Select::make('currency')
                ->label('ارز')
                ->options([
                    'AFN' => 'افغانی',
                    'USD' => 'دالر',
                    'EUR' => 'یورو',
                    'IRR' => 'تومان',
                ])
                ->required(),

            Forms\Components\TextInput::make('paid')
                ->label('مقدار پول')
                ->numeric()
                ->required(),

            Forms\Components\DatePicker::make('date')
                ->label('تاریخ')
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
                Tables\Columns\TextColumn::make('market.name')->label('مارکت')->sortable(),
                Tables\Columns\TextColumn::make('person_name')
                    ->label('نوع شخص')
                    ->getStateUsing(function ($record) {
                        if ($record->customer_id && $record->customer) {
                            return 'مشتری: ' . $record->customer->fullname;
                        }
                        if ($record->staff_id && $record->staff) {
                            return 'کارمند: ' . $record->staff->fullname;
                        }
                        return '-';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')->label('ارز')->searchable(),
                Tables\Columns\TextColumn::make('paid')->label('مبلغ')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('description')->label('توضیحات')->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('تاریخ')
                    ->formatStateUsing(
                        fn($state) =>
                        Jalalian::fromDateTime($state)->format('Y/m/d') . ' - ' . date('g:i A',)
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('زمان ثبت')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخرین ویرایش')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('printContract')
                ->label('چاپ رسید')
                ->icon('heroicon-o-printer')
                ->url(fn($record) => route('outside.print', $record->id))
                ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOutsides::route('/'),
            'create' => Pages\CreateOutside::route('/create'),
            'view' => Pages\ViewOutside::route('/{record}'),
            'edit' => Pages\EditOutside::route('/{record}/edit'),
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
