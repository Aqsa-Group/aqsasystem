<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\DocumentResource\Pages;
use App\Models\Market\Document;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'forkawesome-book';
    protected static ?string $navigationGroup = 'اطلاعات مارکت';
    protected static ?string $navigationLabel = 'اسناد قراردادها';
    protected static ?string $modelLabel = 'سند قرارداد';
    protected static ?string $pluralModelLabel = 'قراردادها';
    protected static ?int $navigationSort = 1;
  

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['admin', 'superadmin' , 'Customer Service']);
    }

     
    public static function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('shopkeeper_id'),
            Hidden::make('shop_id'),
            Hidden::make('market_id'),
            Hidden::make('admin_id'),

            TextInput::make('original_name')
                ->label('نام سند')
                ->disabled(),

            FileUpload::make('signed_image')
                ->label('تصویر سند امضا شده')
                ->image()
                ->optimize('webp')
                ->resize(50)
                ->directory('uploads/signed-documents')
                ->disk('public')
                ->required(fn(string $context) => $context === 'edit'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('market.name')
                    ->label('مارکت')
                    ->searchable()
                    ->badge()
                    ->color('primary'),
    
                TextColumn::make('shop.number')
                    ->label('نمبر دوکان')
                    ->url(fn(Document $record) => route('filament.market.resources.shops.view', ['record' => $record->shop_id]))
                    ->openUrlInNewTab()
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-building-storefront')
                    ->tooltip('نمایش جزئیات دوکان'),
    
                    TextColumn::make('shopkeeper.fullname')
                    ->label('نام دوکاندار')
                    ->url(fn(Document $record) => $record->shopkeeper_id ? route('filament.market.resources.shopkeepers.view', ['record' => $record->shopkeeper_id]) : null)
                    ->openUrlInNewTab()
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-o-user')
                    ->tooltip('نمایش جزئیات دوکاندار'),
                
                    Tables\Columns\TextColumn::make('shop.customer.fullname')
                    ->label('نام خریدار یا گروی کننده')
                    ->url(fn(Document $record) => $record->shop?->customer ? route('filament.market.resources.customers.view', ['record' => $record->shop->customer->id]) : null)
                    ->openUrlInNewTab()
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-user-circle')
                    ->tooltip('نمایش جزئیات مشتری')
                    ->formatStateUsing(fn($state, Document $record) => $record->shop?->customer?->fullname ?? '—'),
                                    
    
                TextColumn::make('created_at')
                    ->label('تاریخ آپلود')
                    ->formatStateUsing(fn($state) => Jalalian::fromDateTime($state)->format('Y/m/d H:i'))
                    ->sortable()
                    ->extraAttributes(['class' => 'text-xs text-gray-500']),
            ])
            ->actions([
                Action::make('download_signed_image_pdf')
                    ->label('دانلود سند امضا شده (PDF)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn(Document $record) => $record->signed_image !== null)
                    ->url(fn(Document $record) => route('contract.signed-image.download', $record))
                    ->openUrlInNewTab()
                    ->color('success'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'edit' => Pages\EditDocument::route('/{record}/edit'), 
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        if ($user->role === 'superadmin') {
            return parent::getEloquentQuery();
        }

        if ($user->role === 'admin') {
            return parent::getEloquentQuery()->where('admin_id', $user->id);
        }

        return parent::getEloquentQuery()->where('admin_id', $user->admin_id);
    }

    public static function canCreate(): bool
    {
        return false;
    }

}
