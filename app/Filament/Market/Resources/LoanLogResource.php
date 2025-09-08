<?php

namespace App\Filament\Market\Resources;

use App\Filament\Market\Resources\LoanLogResource\Pages;
use App\Models\Market\LoanLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Auth;

class LoanLogResource extends Resource
{
    protected static ?string $model = LoanLog::class;

    protected static ?string $navigationIcon = 'vaadin-money-withdraw';
    protected static ?string $navigationLabel = 'لاگ بردگی ها';
    protected static ?string $navigationGroup = 'گزارشات';
    protected static ?string $pluralLabel = 'گزارشات بردگی';

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['superadmin' , 'Financial Manager' , 'admin']);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // اگر فرم ایجاد و ویرایش نیاز است، اینجا می‌توان فیلدها را تعریف کرد
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('loan_id')->label('کد قرضه')->sortable(),
                Tables\Columns\TextColumn::make('person')->label('نوع شخص')->sortable(),
                Tables\Columns\TextColumn::make('related_type')->label('نوع گیرنده')->sortable(),
                Tables\Columns\TextColumn::make('related_id')->label('شناسه گیرنده')->sortable(),
                Tables\Columns\TextColumn::make('currency')->label('ارز')->sortable(),
                Tables\Columns\TextColumn::make('amount')->label('مقدار')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('expanses_type')->label('برداشت از')->sortable(),
                Tables\Columns\TextColumn::make('description')->label('توضیحات')->wrap()->limit(50),
                Tables\Columns\TextColumn::make('date')
                    ->label('تاریخ')
                    ->formatStateUsing(fn($state) => Jalalian::fromDateTime($state)->format('Y/m/d') . ' - ' . date('g:i A', strtotime($state)))
                    ->sortable(),
            ])
            ->filters([
            ])
            ->actions([
                
                Tables\Actions\Action::make('printContract')
                    ->label('چاپ بردگی')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('loan.print', $record->id))
                    ->openUrlInNewTab(),


             
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // در صورت داشتن RelationManagers اینجا اضافه کن
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoanLogs::route('/'),
            // 'create' => Pages\CreateLoanLog::route('/create'),
            // 'edit' => Pages\EditLoanLog::route('/{record}/edit'),
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
