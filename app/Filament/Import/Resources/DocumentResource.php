<?php

namespace App\Filament\Import\Resources;

use App\Filament\Import\Resources\DocumentResource\Pages;
use App\Filament\Import\Resources\DocumentResource\RelationManagers;
use App\Models\Import\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Morilog\Jalali\Jalalian;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-printer';
    protected static ?string $navigationLabel = 'لیست فاکتورها';
    protected static ?string $pluralModelLabel = 'لیست فاکتورها';
    protected static ?string $navigationGroup = 'بخش خرید و فروش';
    protected static ?int $navigationSort = 4;









    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('شماره فاکتور')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('sale_type')
                    ->label('نوع فروش')
                    ->formatStateUsing(fn(string $state) => $state === 'wholesale' ? 'عمده' : 'پرچون')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('buyer_name')
                    ->label('نام خریدار')
                    ->default('-')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('مبلغ کل فروش')
                    ->money('afn', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('paid_amount')
                    ->label('مبلغ دریافتی')
                    ->money('afn', true)
                    ->default('-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ فروش')
                    ->formatStateUsing(
                        fn($state) =>
                        Jalalian::fromDateTime($state)->format('Y/m/d h:i A')
                    )
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('file_path')
                    ->label('فاکتور')
                    ->url(fn($record) => asset($record->file_path), true)
                    ->icon('heroicon-o-printer')
                    ->tooltip('مشاهده/چاپ فاکتور'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sale_type')
                    ->label('نوع فروش')
                    ->options([
                        'retail'    => 'پرچون',
                        'wholesale' => 'عمده',
                    ]),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            // 'create' => Pages\CreateDocument::route('/create'),
            // 'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
