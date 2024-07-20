<?php

namespace App\Filament\Resources\HR;

use App\Filament\Resources\HR\EmployeeActivitiesResource\Pages;
use App\Filament\Resources\HR\EmployeeActivitiesResource\RelationManagers;
use App\HasCreateAnother;
use App\Models\HR\Employee;
use App\Models\HR\EmployeeActivities;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeActivitiesResource extends Resource
{
    use \App\HasConfigurations;
    use HasCreateAnother;

    protected static ?string $model = EmployeeActivities::class;

    protected static ?string $navigationIcon = 'carbon-user-activity';
    protected static ?string $label = "چاڵاکی";
    protected static ?string $pluralLabel = "چاڵاکی یەکان";
    protected static ?string $navigationGroup = 'سەرچاوە مرۆی یەکان';

    public static function setFields($get,$set)
    {

        if($get('type') == 'salary'){
            $set('last_salary',Employee::find($get('employee_id'))?->last_salary->format('Y-m-d H:i:s') ?? now());
            $set('salary',number_format(Employee::find($get('employee_id'))?->salary??0,2));
            $set('punish',Employee::getTypeAmount($get('employee_id'),$get('last_salary'),$get('created_at'),'punish'));
            $set('bonus',Employee::getTypeAmount($get('employee_id'),$get('last_salary'),$get('created_at'),'bonus'));
            $set('absence',Employee::getTypeAmount($get('employee_id'),$get('last_salary'),$get('created_at'),'absence'));
            $set('overtime',Employee::getTypeAmount($get('employee_id'),$get('last_salary'),$get('created_at'),'overtime'));
            $set('advance',Employee::getTypeAmount($get('employee_id'),$get('last_salary'),$get('created_at'),'advance'));
            $set('amount',(((Employee::find($get('employee_id'))?->salary??0) + $get('bonus') + $get('overtime'))-($get('punish') + $get('absence') + $get('advance'))));

        }

    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::selectField('employee_id',EmployeeResource::class)
                    ->relationship('employee', 'name')
                    ->label('ستاف')
                    ->afterStateUpdated(fn(Forms\Get $get,Forms\Set $set)=>static::setFields($get,$set))
                    ->live()
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options(static::$model::getTypes())
                    ->label('جۆر')
                    ->live()
                    ->afterStateUpdated(fn(Forms\Get $get,Forms\Set $set)=>static::setFields($get,$set))
                    ->native(0)
                    ->required(),
                Forms\Components\DateTimePicker::make('last_salary')
                    ->label('کۆتا مووچەدان')
                    ->disabled()
                    ->hidden(fn(Forms\Get $get)=>$get('type') != 'salary'),
                Forms\Components\DateTimePicker::make('created_at')
                    ->default(now())
                    ->live()
                    ->afterStateUpdated(fn(Forms\Get $get,Forms\Set $set)=>static::setFields($get,$set))
                    ->label('بەروار')
                    ->required(),
                Forms\Components\TextInput::make('salary')
                    ->label('مووچە')
                    ->hidden(fn(Forms\Get $get)=>$get('type') != 'salary')
                    ->disabled(),
                Forms\Components\TextInput::make('punish')
                    ->label('سزا')
                    ->hidden(fn(Forms\Get $get)=>$get('type') != 'salary')
                    ->disabled(),
                Forms\Components\TextInput::make('bonus')
                    ->label('پاداشت')
                    ->hidden(fn(Forms\Get $get)=>$get('type') != 'salary')
                    ->disabled(),
                Forms\Components\TextInput::make('absence')
                    ->label('ئامادەنەبوون')
                    ->hidden(fn(Forms\Get $get)=>$get('type') != 'salary')
                    ->disabled(),
                Forms\Components\TextInput::make('overtime')
                    ->label('کاتی زیادە')
                    ->hidden(fn(Forms\Get $get)=>$get('type') != 'salary')
                    ->disabled(),
                Forms\Components\TextInput::make('advance')
                    ->label('مووچەی پێشوەختە')
                    ->hidden(fn(Forms\Get $get)=>$get('type') != 'salary')
                    ->disabled(),
                Forms\Components\TextInput::make('amount')
                    ->label('بڕ')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('note')
                    ->label('تێبینی')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('ستاف')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('بڕی پارە')
                    ->numeric()
                    ->formatStateUsing(fn($state,$record)=>number_format($state,$record->currency->decimal_places))
                    ->suffix(fn($record)=>(' '.$record->currency->symbol))
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('جۆر')
                    ->formatStateUsing(fn($state)=>static::$model::getTypes()[$state]),
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
                Tables\Filters\SelectFilter::make('type')
                    ->options(static::$model::getTypes())
                    ->label('جۆر')
                ->native(0)
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
            'index' => Pages\ManageEmployeeActivities::route('/'),
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
