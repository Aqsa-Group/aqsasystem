<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\DepositLogResource\Pages;
use App\Models\Market\DepositLog;
use App\Models\Market\Market;
use App\Models\Market\Shop;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Morilog\Jalali\Jalalian;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class DepositLogResource extends Resource
{
    protected static ?string $model = DepositLog::class;
    protected static ?string $navigationIcon = 'letsicon-arhive-alt-add-light';
    protected static ?string $navigationGroup = 'گزارشات';
    protected static ?string $navigationLabel = 'لاگ رسید دوکان ها';
    protected static ?string $pluralModelLabel = 'لاگ پرداختی‌ها';
    protected static ?string $modelLabel = 'لاگ پرداختی';

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin', 'Financial Manager', 'admin']);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('market.name')->label('مارکت')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('shop.number')->label('شماره دوکان')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('shopkeeper.fullname')->label('دوکاندار')->searchable(),
                Tables\Columns\TextColumn::make('old_paid')->label('پرداخت قبلی'),
                Tables\Columns\TextColumn::make('new_paid')->label('پرداخت جدید'),
                Tables\Columns\TextColumn::make('old_remained')->label('باقی قبلی'),
                Tables\Columns\TextColumn::make('new_remained')->label('باقی جدید'),
    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت رسید')
                    ->formatStateUsing(function ($state) {
                        $dt = Carbon::parse($state)->setTimezone('Asia/Kabul');
                        return Jalalian::fromDateTime($dt)->format('Y/m/d') . ' - ' . $dt->format('g:i A');
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('market_id')
                    ->label('مارکت')
                    ->options(\App\Models\Market\Market::pluck('name', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        if (blank($data['value'] ?? null)) return $query;
                        return $query->where('market_id', $data['value']);
                    }),
    
                Tables\Filters\SelectFilter::make('shop_id')
                    ->label('دوکان')
                    ->options(\App\Models\Market\Shop::pluck('number', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        if (blank($data['value'] ?? null)) return $query;
                        return $query->where('shop_id', $data['value']);
                    }),
    
                Tables\Filters\SelectFilter::make('expanses_type')
                    ->label('نوع هزینه')
                    ->options(fn() => DepositLog::query()->distinct()->pluck('expanses_type', 'expanses_type')->toArray())
                    ->query(function (Builder $query, array $data): Builder {
                        if (blank($data['value'] ?? null)) return $query;
                        return $query->where('expanses_type', $data['value']);
                    }),
    
                Tables\Filters\Filter::make('created_at')
                    ->label('تاریخ ثبت')
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
                            ->when($data['from'], fn($q) => $q->where('created_at', '>=', $data['from']))
                            ->when($data['to'], fn($q) => $q->where('created_at', '<=', $data['to']));
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
             
                Tables\Actions\Action::make('print')
                  ->icon('heroicon-o-printer')
                    ->label('چاپ')
                    ->url(fn ($record) => route('deposit-log.print', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                
            
            ]);
    }
    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepositLogs::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        // سوپرادمین همه رکوردها را می‌بیند
        if ($user->role === 'superadmin') {
            return parent::getEloquentQuery()->with([
                'user',
                'market',
                'shop',
                'shopkeeper',
            ]);
        }

        return parent::getEloquentQuery()
            ->with([
                'user',
                'market',
                'shop',
                'shopkeeper',
            ])
            ->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id);
    }
}
