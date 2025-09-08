<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\CustomerResource\Pages;
use App\Models\Market\Customer;
use App\Models\Market\Market;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'lucide-user-square';
    protected static ?string $navigationGroup = "اطلاعات مارکت";
    protected static ?string $navigationLabel = "مشتریان";
    protected static ?string $modelLabel = "مشتری";
    protected static ?string $pluralModelLabel = "مشتریان";


    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['admin', 'superadmin' , 'Customer Service']);
    }
    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $adminId = $user->role === 'admin' ? $user->id : $user->admin_id;

        return $form
            ->schema([
                Forms\Components\Select::make('market_id')
                    ->label('مارکت مربوطه')
                    ->placeholder('نام مارکت را انتخاب کنید')
                    ->options(fn() => Market::where('admin_id', $adminId)->pluck('name', 'id'))
                    ->required()
                    ->reactive(),

                Forms\Components\Hidden::make('admin_id')
                    ->default($adminId),

                Forms\Components\TextInput::make('fullname')
                    ->label('نام ')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('father_name')
                    ->label('نام پدر')
                    ->required()
                    ->maxLength(255),


                 Forms\Components\TextInput::make('grand_father')
                    ->label('نام پدرکلان')
                    ->required()
                    ->maxLength(255),


                Forms\Components\TextInput::make('phone')
                    ->label('شماره تلفن')
                    ->tel()
                    ->required()
                    ->numeric()
                    ->maxLength(15),

                Forms\Components\TextInput::make('address')
                    ->label('آدرس')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('job')
                    ->label('وظیفه')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('id_number')
                    ->label('شماره تذکره')
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('id_card_image')
                    ->label('عکس تذکره')
                    ->image()
                    ->optimize('webp')
                    ->resize(50)
                    ->directory('uploads/Customer')
                    ->visibility('public')
                    ->required(),

                Forms\Components\FileUpload::make('profile_image')
                    ->label('عکس مشتری')
                    ->image()
                    ->directory('uploads/Customer')
                    ->visibility('public')
                    ->optimize('webp')
                    ->resize(50) 
                    ->required(),

                    Forms\Components\FileUpload::make('warranty_document')
                    ->label('ضمانت خط مشتری')
                    ->image()
                    ->directory('uploads/Customer')
                    ->visibility('public')
                    ->optimize('webp')
                    ->resize(50) 
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('market.name')
                    ->label('مارکت')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('fullname')
                    ->label('نام کامل')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('father_name')
                    ->label('نام پدر')
                    ->searchable(),

                    Tables\Columns\TextColumn::make('balance_afn')->label('افغانی')->sortable(),
                    Tables\Columns\TextColumn::make('balance_usd')->label('دالر')->sortable(),
                    Tables\Columns\TextColumn::make('balance_eur')->label('یورو')->sortable(),
                    Tables\Columns\TextColumn::make('balance_irr')->label('تومان')->sortable(),
                    Tables\Columns\TextColumn::make('rent_money')->label('مجموعه کرایه های جمع شده')->sortable(),




                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->sortable()
                    ->dateTime()
                    ->formatStateUsing(fn($state) => Jalalian::fromDateTime($state)->format('Y/m/d - H:i')),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخرین به‌روزرسانی')
                    ->sortable()
                    ->dateTime()
                    ->formatStateUsing(fn($state) => Jalalian::fromDateTime($state)->format('Y/m/d - H:i'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('market_id')
                    ->label('فیلتر مارکت')
                    ->options(fn() => Market::pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // اینجا اگر Relation Manager دارید اضافه کنید
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
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
