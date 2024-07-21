<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\StudentPaymentResource\Pages;
use App\Filament\Resources\Finance\StudentPaymentResource\RelationManagers;
use App\Filament\Resources\Finance\StudentPaymentResource\Widgets\StudentPaymentWidget;
use App\Filament\Resources\Settings\CurrencyResource;
use App\Filament\Resources\Student\StudentResource;
use App\HasCreateAnother;
use App\Models\Finance\StudentPayment;
use App\Models\HR\Employee;
use App\Models\Settings\Currency;
use App\Models\Student\Student;
use Filament\Actions\ReplicateAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Session;

class StudentPaymentResource extends Resource
{
    use \App\HasConfigurations;
    use HasCreateAnother;

    protected static ?int $navigationSort = 5;

    protected static ?string $model = StudentPayment::class;

    protected static ?string $navigationIcon = 'polaris-payment-filled-icon';
    protected static ?string $pluralLabel = "پارەدانەکان";
    protected static ?string $label = "پارەدان";
    protected static ?string $navigationGroup = 'ژمێریاری';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::selectField('student_id',StudentResource::class)
                    ->label(StudentResource::getNavigationLabel())
                    ->relationship('student', 'name')
                    ->getOptionLabelFromRecordUsing(function (Model $record) {
                        return "<div class='flex items-center align-middle gap-4'>
                                <img class='w-14 h-14 rounded-full border-2 border-primary-500' src='".asset("storage/".$record->image)."'>
                                <div>
                                <h3 class='text-base font-semibold text-primary-500'>{$record->name}</h3>
                                <h3 class='text-base font-semibold'>{$record->code}</h3>
                                <h3 class='text-sm text-primary-500'> {$record->due_amount} $</h3>
                                </div>

                            </div>";
                    })
                    ->live()
                    ->searchable(['name','code'])
                    ->suffixIcon(StudentResource::getNavigationIcon())
                    ->afterStateUpdated(fn($state,Forms\Set $set)=>$set('amount',Employee::find($state)?->per_month_amount??0))
                     ->allowHtml()
                    ->required(),
                Forms\Components\Select::make('currency_id')
                    ->relationship('currency', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->default(1)
                    ->label(CurrencyResource::getNavigationLabel())
                    ->suffixIcon(CurrencyResource::getNavigationIcon())
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('بڕ')
                    ->required()
                    ->suffix(fn(Forms\Get $get)=>Currency::find($get('currency_id'))?->symbol)
                    ->numeric(),
                Forms\Components\DateTimePicker::make('created_at')
                    ->label('بەروار')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('note')
                    ->label('تێبینی')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->default(null),

            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id','desc')
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('ناو')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('بڕی پارە')
                    ->suffix(fn ($record)=>(' '. $record->currency->symbol))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('سڕینەوە')
                    ->dateTime('Y-m-d')
                    ->formatStateUsing(fn($state)=>"سڕینەوە: ". $state->format('Y-m-d'))
                    ->color(\Filament\Support\Colors\Color::Red)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('دروستکردن')
                    ->dateTime('Y-m-d')
                    ->formatStateUsing(fn($state)=>"دروستکردن: ". $state->format('Y-m-d'))
                    ->color(\Filament\Support\Colors\Color::Green)
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('نوێکردنەوە')
                    ->dateTime('Y-m-d')
                    ->formatStateUsing(fn($state)=>"نوێکردنەوە : ". $state->format('Y-m-d'))
                    ->color(\Filament\Support\Colors\Color::Amber)
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('created_at')
                                ->label('دروستکردن'),
                            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('updated_at')
                                ->label('نوێکردنەوە'),
                            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('deleted_at')
                                ->label('سڕینەوە'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\Action::make('print')
                    ->label('چاپکردن')
                    ->icon('fas-print')
                    ->color(Color::Green)
                    ->action(function ($record){
                        return redirect(self::getUrl('print',['record'=>$record->id]));
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageStudentPayments::route('/'),
            'print'=>Pages\PrintReciept::route('/{record}/print')
        ];
    }



    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
