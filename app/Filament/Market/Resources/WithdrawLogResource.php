<?php

namespace App\Filament\Market\Resources;
use App\Models\Market\WithdrawLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms;
use Morilog\Jalali\Jalalian;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WithdrawLogResource extends Resource
{

    
    protected static ?string $model = WithdrawLog::class;
    protected static ?string $navigationIcon = 'iconoir-safe-arrow-right';
    protected static ?string $navigationLabel = 'لاگ برداشت‌ها از صندوق';
    protected static ?string $navigationGroup = 'گزارشات';
    protected static ?string $pluralLabel = 'گزارشات صندوق';

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin' , 'Financial Manager' , 'admin']);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('نمبر')->sortable(),
                TextColumn::make('expanses_type')->label('برداشت از')->sortable()->searchable(),
                TextColumn::make('currency')->label('ارز')->sortable(),
                TextColumn::make('amount')->label('مقدار')->money('AFN', true)->sortable(),
                TextColumn::make('recipient_name')
                    ->label('تحویل داده شده به')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')->label('توضیحات')->limit(50)->wrap(),
                TextColumn::make('created_at')
                    ->label('تاریخ برداشت')
                    ->sortable()
                    ->formatStateUsing(
                        fn($state) =>
                        Jalalian::fromDateTime($state)->format('Y/m/d') .
                            ' - ' .
                            date('g:i A', strtotime($state))
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('expanses_type')
                    ->label('نوع برداشت')
                    ->options(fn() => WithdrawLog::query()->distinct()->pluck('expanses_type', 'expanses_type')->toArray()),
                Tables\Filters\SelectFilter::make('currency')
                    ->label('ارز')
                    ->options([
                        'AFN' => 'افغانی',
                        'USD' => 'دالر',
                        'EUR' => 'یورو',
                        'IRR' => 'تومان',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->label('تاریخ برداشت')
                    ->form([
                        Forms\Components\DateTimePicker::make('from')
                            ->label('از تاریخ و ساعت')
                            ->withoutSeconds()
                            ->displayFormat('Y-m-d h:i A'),
                        Forms\Components\DateTimePicker::make('to')
                            ->label('تا تاریخ و ساعت')
                            ->withoutSeconds()
                            ->displayFormat('Y-m-d h:i A'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn($q) => $q->where('created_at', '>=', $data['from']))
                            ->when($data['to'] ?? null, fn($q) => $q->where('created_at', '<=', $data['to']));
                    }),
            ])
            ->actions([
                
                Tables\Actions\Action::make('printContract')
                    ->label('چاپ برداشت')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('recipt.print', $record->id))
                    ->openUrlInNewTab(),
             
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Market\Resources\WithdrawLogResource\Pages\ListWithdrawLogs::route('/'),

        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    private static function convertPersianToEnglish(string $string): string
    {
        return str_replace(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $string
        );
    }


    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        if ($user->role === 'superadmin') {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id);
    }
}
