<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\ExpenseResource\Pages;
use App\Filament\Resources\Finance\ExpenseResource\RelationManagers;
use App\HasCreateAnother;
use App\Models\Finance\Expense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseResource extends Resource
{
    use \App\HasConfigurations;
    use HasCreateAnother;


    protected static ?string $model = Expense::class;
    protected static ?int $navigationSort = 7;


    protected static ?string $navigationIcon = 'fluentui-expand-up-right-24';
    protected static ?string $pluralLabel = "خەرجی یەکان";
    protected static ?string $label = "خەرجی";
    protected static ?string $navigationGroup = 'ژمێریاری';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::selectField('expense_type_id',ExpenseTypeResource::class)
                    ->relationship('expenseType', 'name')
                    ->label('جۆری خەرجی')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label('ناوەڕۆک')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('currency_id')
                    ->relationship('currency', 'name')
                    ->label('جۆری دراو')
                    ->searchable()
                    ->default(1)
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('بڕ')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('created_at')
                    ->label('بەروار')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('note')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('expenseType.name')
                    ->label('جۆری خەرجی')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('ناوەڕۆک')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('بڕ')
                    ->suffix(fn($record)=>(' '. $record->currency->symbol))
                    ->formatStateUsing(fn($state,$record)=>number_format($state,$record->currency->decimal_places))
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('سڕینەوە')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('بەروار')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('نوێکردنەوە')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('created_at')
                                ->label('دروستکردن'),
                            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('updated_at')
                                ->label('نوێکردنەوە'),
                            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('deleted_at')
                                ->label('سڕینەوە'),
                Tables\Filters\SelectFilter::make('expense_type_id')
                    ->relationship('expenseType', 'name')
                    ->label('جۆری خەرجی')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('currency_id')
                    ->relationship('currency', 'name')
                    ->label('جۆری دراو')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ManageExpenses::route('/'),
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
