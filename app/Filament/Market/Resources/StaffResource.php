<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\StaffResource\Pages;
use App\Models\Market\Staff;
use App\Models\Market\Market;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $navigationIcon = 'entypo-add-user';
    protected static ?string $navigationGroup = "اطلاعات مارکت";
    protected static ?string $navigationLabel = "ثبت کارمندان";
    protected static ?string $modelLabel = "کارمند";
    protected static ?string $pluralModelLabel = "کارمندان";


    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['admin', 'superadmin', 'Customer Service
    ']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('market_id')
                ->label('مارکت مربوطه')
                ->placeholder('مارکت را انتخاب کنید')
                ->options(function () {
                    $user = Auth::user();
                    return $user->role === 'superadmin'
                        ? Market::pluck('name', 'id')
                        : Market::where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id)
                        ->pluck('name', 'id');
                })
                ->required(),

            Forms\Components\Hidden::make('admin_id')
                ->default(fn() => Auth::user()->role === 'admin'
                    ? Auth::user()->id
                    : Auth::user()->admin_id),

            Forms\Components\TextInput::make('fullname')->label('نام کامل')->required()->maxLength(255),
            Forms\Components\TextInput::make('father_name')->label('نام پدر')->required()->maxLength(255),
            Forms\Components\TextInput::make('phone')->label('شماره تلفن')->tel()->required()->numeric(),
            Forms\Components\TextInput::make('address')->label('آدرس')->required()->maxLength(255),
            Forms\Components\TextInput::make('job')->label('وظیفه')->required()->maxLength(255),
            Forms\Components\TextInput::make('salary')->label('معاش')->numeric()->required(),
            Forms\Components\TextInput::make('id_number')->label('نمبر تذکره')->required()->maxLength(255),

            Forms\Components\DatePicker::make('contract_start')->label('تاریخ شروع قرارداد')->jalali()->required(),
            Forms\Components\DatePicker::make('contract_end')->label('تاریخ ختم قرارداد')->jalali()->required(),

            Forms\Components\FileUpload::make('warranty_image')
                ->label('ضمانت خط')
                ->image()
                ->optimize('webp')
                ->resize(50)
                ->directory('uploads/staff/warrant_image')
                ->visibility('public')
                ->required(),

            Forms\Components\FileUpload::make('id_card_image')
                ->label('عکس تذکره')
                ->image()
                ->optimize('webp')
                ->resize(50)
                ->directory('uploads/staff/id_card')
                ->visibility('public')
                ->required(),

            Forms\Components\FileUpload::make('profile_image')
                ->label('عکس کارمند')
                ->image()
                ->optimize('webp')
                ->resize(50)
                ->directory('uploads/staff/profile')
                ->visibility('public')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('market.name')->label('مارکت')
                ->searchable()
                ->sortable()
                ->badge()
                ->color('primary')
                ->icon('heroicon-o-building-storefront'),

            Tables\Columns\TextColumn::make('fullname')->label('نام کامل')->searchable(),
            Tables\Columns\TextColumn::make('father_name')->label('نام پدر')->searchable(),
            Tables\Columns\TextColumn::make('phone')->label('شماره تلفن')->sortable(),
            Tables\Columns\TextColumn::make('address')->label('آدرس')->searchable(),
            Tables\Columns\TextColumn::make('job')->label('وظیفه')->searchable(),
            Tables\Columns\TextColumn::make('salary')->label('معاش کارمند')->searchable(),

        

            Tables\Columns\TextColumn::make('created_at')->label('ایجاد شده')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updated_at')->label('آخرین تغییر')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print_contract')
                    ->label('پرینت قرارداد')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('staff.contract.print', $record->id))
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'view' => Pages\ViewStaff::route('/{record}'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
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
