<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\ExchangeResource\Pages;
use App\Models\Market\Accounting;
use App\Models\Market\Exchange;
use App\Services\ExchangePdfService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class ExchangeResource extends Resource
{
    protected static ?string $model = Exchange::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationGroup = 'بخش مالی';

    protected static ?string $navigationLabel = 'تسویه نشده';

    protected static ?string $pluralModelLabel = 'تبادله';

    protected static ?string $modelLabel = 'تبدیل';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('from_type')
                    ->label('از')
                    ->options(fn () => Accounting::query()->where('admin_id', Auth::id())->whereNotNull('expanses_type')->where('expanses_type', '!=', '')->distinct()->orderBy('expanses_type')->pluck('expanses_type', 'expanses_type')->toArray())
                    ->required(),

                Forms\Components\Select::make('to_type')
                    ->label('به')
                    ->options(fn () => Accounting::query()->where('admin_id', Auth::id())->whereNotNull('expanses_type')->where('expanses_type', '!=', '')->distinct()->orderBy('expanses_type')->pluck('expanses_type', 'expanses_type')->toArray())
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->label('مقدار')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('currency')
                    ->label('واحد پول')
                    ->options([
                        'AFN' => 'افغانی',
                        'USD' => 'دالر',
                        'TOMAN' => 'تومان',
                        'EUR' => 'یورو',
                    ])
                    ->default('AFN')
                    ->required(),

                Forms\Components\DateTimePicker::make('created_at')
                    ->label('تاریخ ثبت')
                    ->jalali()
                    ->default(now())
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('توضیحات')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
                    ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('شماره')->sortable(),
                Tables\Columns\TextColumn::make('from_type')->label('از')->searchable(),
                Tables\Columns\TextColumn::make('to_type')->label('به')->searchable(),
                Tables\Columns\TextColumn::make('amount')->label('مقدار')->sortable(),

                Tables\Columns\TextColumn::make('currency')
                    ->label('واحد پول')
                    ->badge()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => [
                        'AFN' => 'افغانی',
                        'USD' => 'دالر',
                        'TOMAN' => 'تومان',
                        'EUR' => 'یورو',
                    ][$state] ?? $state),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->formatStateUsing(fn ($state) => $state ? jdate($state)->format('Y/m/d H:i') : '—')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('از تاریخ')
                            ->jalali(),

                        Forms\Components\DatePicker::make('created_until')
                            ->label('تا تاریخ')
                            ->jalali(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $from = null;
                        $until = null;

                        // تبدیل دقیق تاریخ شمسی به میلادی برای دیتابیس
                        if (!empty($data['created_from'])) {
                            $dateStr = str_replace('-', '/', $data['created_from']);

                            if (preg_match('/^1[34]\d{2}/', $dateStr)) { // تشخیص سال شمسی
                                $from = Jalalian::fromFormat('Y/m/d', $dateStr)->toCarbon()->startOfDay();
                            } else {
                                $from = Carbon::parse($dateStr)->startOfDay();
                            }
                        }

                        if (!empty($data['created_until'])) {
                            $dateStr = str_replace('-', '/', $data['created_until']);

                            if (preg_match('/^1[34]\d{2}/', $dateStr)) {
                                $until = Jalalian::fromFormat('Y/m/d', $dateStr)->toCarbon()->endOfDay();
                            } else {
                                $until = Carbon::parse($dateStr)->endOfDay();
                            }
                        }

                        return $query
                            ->when($from, fn (Builder $q, $date) => $q->where('created_at', '>=', $date))
                            ->when($until, fn (Builder $q, $date) => $q->where('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $dateStr = str_replace('-', '/', $data['created_from']);
                            $fromLabel = preg_match('/^1[34]\d{2}/', $dateStr)
                                ? $dateStr
                                : jdate($dateStr)->format('Y/m/d');

                            $indicators[] = Tables\Filters\Indicator::make('از تاریخ: ' . $fromLabel)->removeField('created_from');
                        }

                        if ($data['created_until'] ?? null) {
                            $dateStr = str_replace('-', '/', $data['created_until']);
                            $untilLabel = preg_match('/^1[34]\d{2}/', $dateStr)
                                ? $dateStr
                                : jdate($dateStr)->format('Y/m/d');

                            $indicators[] = Tables\Filters\Indicator::make('تا تاریخ: ' . $untilLabel)->removeField('created_until');
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('print')
                    ->label('چاپ')->icon('heroicon-o-printer')->color('success')
                    ->action(function (Exchange $record) {
                        $pdf = ExchangePdfService::render([$record], 'چاپ تبادله شماره ' . $record->id);
                        return response()->streamDownload(fn () => print $pdf, "exchange-{$record->id}-" . now()->format('Ymd-His') . '.pdf', ['Content-Type' => 'application/pdf']);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('printAll')
                    ->label('چاپ همه (با فیلتر)')->icon('heroicon-o-printer')->color('success')
                    ->action(function ($livewire) {
                        $exchanges = $livewire->getFilteredTableQuery()->orderByDesc('id')->get();
                        $pdf = ExchangePdfService::render($exchanges, 'لیست تبادلات');
                        return response()->streamDownload(fn () => print $pdf, 'exchanges-all-' . now()->format('Ymd-His') . '.pdf', ['Content-Type' => 'application/pdf']);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('printSelected')
                        ->label('چاپ انتخاب‌شده‌ها')->icon('heroicon-o-printer')->color('success')
                        ->action(function ($records) {
                            $pdf = ExchangePdfService::render($records, 'چاپ تبادلات انتخاب‌شده');
                            return response()->streamDownload(fn () => print $pdf, 'exchanges-selected-' . now()->format('Ymd-His') . '.pdf', ['Content-Type' => 'application/pdf']);
                        })->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExchanges::route('/'),
            'create' => Pages\CreateExchange::route('/create'),
            'edit' => Pages\EditExchange::route('/{record}/edit'),
        ];
    }
}
