<?php

namespace App\Filament\Import\Resources;

use App\Filament\Import\Resources\CustomerResource\Pages;
use App\Filament\Resources\WarehouseResource\Pages\Resources\CustomerResource\RelationManagers;
use App\Models\Import\Customer;
use App\Models\Import\Safe;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'carbon-customer';
    protected static ?string $navigationGroup = 'حسابداری';

    protected static ?string $navigationLabel =  'مشتریان';
    protected static ?string $modelLabel =  'مشتری';
    protected static ?string $pluralModelLabel =  'مشتریان';
    protected static ?int $navigationSort =6;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Hidden::make('user_id')
                ->default(fn () => Auth::id()),
    
                Forms\Components\TextInput::make('name')->label('نام')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('father_name')->label('نام پدر')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('grand-father')->label('نام پدرکلان')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')->label('آدرس')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('phone')->label('شماره تلفن')
                    ->tel()
                    ->required()
                    ->numeric(),
                Forms\Components\FileUpload::make('customer_image')->label('عکس مشتری')
                    ->image()
                    ->directory('public/Cutomer/id_image')
                    ->visibility('public')
                    ->optimize('webp')
                    ->resize(50),
                Forms\Components\FileUpload::make('customer_id_card')->label('عکی تذکره')
                    ->image()
                    ->directory('public/Cutomer/image')
                    ->visibility('public')
                    ->optimize('webp')
                    ->resize(50),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('نام')->searchable(),
                Tables\Columns\TextColumn::make('father_name')->label('نام پدر')->searchable(),
                Tables\Columns\TextColumn::make('grand-father')->label('نام پدرکلان')->searchable(),
                Tables\Columns\TextColumn::make('address')->label('آدرس')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('شماره تلفن')->sortable(),

                Tables\Columns\TextColumn::make('total_loan')
                    ->label('مجموع قرضه')->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state)),
                Tables\Columns\TextColumn::make('total_receipt')
                    ->label('مجموع رسید')->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state)),
                Tables\Columns\TextColumn::make('remaining_loan')
                    ->label('باقی‌مانده')->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state)),



                Tables\Columns\TextColumn::make('created_at')->label('تاریخ ایجاد')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
          
            ->actions([
                Tables\Actions\Action::make('add_receipt')
                    ->label('ثبت رسید')
                    ->icon('heroicon-o-currency-dollar')
                    ->modalHeading('ثبت رسید مشتری')
                    ->modalWidth('sm')
                    ->form([
                        Forms\Components\TextInput::make('loan_recipt')
                            ->label('مبلغ رسید')
                            ->numeric()
                            ->required()
                            ->prefix('افغانی')
                            ->extraAttributes(['class' => 'text-lg text-center']),
                    ])
                    ->action(function ($record, array $data) {
                        if ($data['loan_recipt'] > $record->remaining_loan) {
                            Notification::make()
                                ->title('خطا: مبلغ رسید بیشتر از باقی‌مانده قرض است!')
                                ->danger()
                                ->send();
                            return;
                        }
            
                        $reminded = max(0, $record->remaining_loan - $data['loan_recipt']);
            
                
                        \App\Models\Import\Loan::create([
                            'customer_id' => $record->id,
                            'loan_recipt' => $data['loan_recipt'],
                            'amount'      => 0,
                            'reminded'    => $reminded,
                            'type'        => 'رسید',
                            'date'        => now(),
                        ]);
            
                        if ($reminded === 0) {
                            $record->update([
                                'loan_amount'    => 0,
                                'loan_recipt'    => 0,
                                'remaining_loan' => 0,
                            ]);
                        } else {
                            $record->update([
                                'loan_recipt'    => $record->loan_recipt + $data['loan_recipt'],
                                'remaining_loan' => $reminded,
                            ]);
                        }
            
                        $safe = Safe::firstOrCreate([], [
                            'total'       => 0,
                            'today'       => 0, 
                            'last_update' => now()->toDateString(),
                        ]);
            
                      
                        $safe->total += $data['loan_recipt'];
                        $safe->save();
            
                        Notification::make()
                            ->title('رسید ثبت شد.')
                            ->body('باقی‌مانده بدهی مشتری: ' . number_format($reminded) . ' افغانی')
                            ->success()
                            ->send();
                    })
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();

    if (Auth::user()?->role === 'superadmin') {
        return $query; 
    }

   
    return $query->where('user_id', Auth::id());
}
}
