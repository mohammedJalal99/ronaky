<?php

namespace App\Filament\Resources\HR;

use App\Filament\Resources\HR\EmployeeResource\Pages;
use App\Filament\Resources\HR\EmployeeResource\RelationManagers;
use App\Models\HR\Employee;
use App\Models\Settings\Currency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class EmployeeResource extends Resource
{
    use \App\HasConfigurations;

    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'clarity-employee-group-solid';
    protected static ?string $label = "ستاف";
    protected static ?string $pluralLabel = "ستاف";
    protected static ?string $navigationGroup = 'سەرچاوە مرۆی یەکان';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('زانیاری کەسی')
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('ناو')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('ژمارەی مۆبایل')
                            ->tel()
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\Select::make('gender')
                            ->label('ڕەگەز')
                            ->options([
                                'male'=>"نێر",
                                "female"=>"مێ"
                            ])
                            ->default('male')
                            ->native(0)
                            ->required(),
                        Forms\Components\DatePicker::make('date_of_birth')
                        ->label('لەدایکبوون'),
                        Forms\Components\TextInput::make('certification')
                            ->label("بڕوانامە")
                            ->datalist([
                                'بەکالۆریۆس',
                                'دبلۆم'
                            ])
                            ->maxLength(255)
                            ->default(null),
                    ])->columns(1),
                Forms\Components\Section::make('تایبەت بە کۆمپانیا')
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\DatePicker::make('hire_date')
                            ->label('دامەزراندن')
                            ->required(),
                        Forms\Components\DatePicker::make('termination_date')
                            ->label('وازهێنان'),
                        Forms\Components\Select::make('currency_id')
                            ->relationship('currency', 'name')
                            ->searchable()
                            ->live()
                            ->preload()
                            ->label('جۆری مووچە')
                            ->default(1)
                            ->required(),
                        Forms\Components\TextInput::make('salary')
                            ->label('مووچە')
                            ->required()
                            ->numeric()
                            ->suffix(fn(Forms\Get $get)=>Currency::find($get('currency_id'))?->symbol),
                        Forms\Components\TextInput::make('position')
                            ->label('پیشە')
                            ->datalist([
                                'مامۆستا',
                                'چاودێر'
                            ])
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->default(null),
                        Forms\Components\TextInput::make('note')
                            ->label('تێبینی')
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->default(null),
                        Forms\Components\FileUpload::make('image')
                            ->label('وێنە')
                            ->avatar()
                            ->columnSpanFull()
                            ->image(),
                    ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id','desc')
            ->columns([
                Tables\Columns\ImageColumn::make('image')->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('ناو')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('مۆبایل')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('ڕەگەز')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(fn($state)=>['male'=>"نێر","female"=>"مێ"][$state]),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('لەدایکبوون')
                    ->date('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('پیشە')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hire_date')
                    ->label('دامەزراندن')
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('termination_date')
                    ->label('وازهێنان')
                    ->date('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('salary')
                    ->label("مووچە")
                    ->suffix(fn($record)=>(' ').$record->currency->symbol)
                    ->formatStateUsing(fn($state,$record)=>number_format($state,$record->currency->decimal_places))
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('certification')
                    ->label('ىڕوانامە')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),

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
                DateRangeFilter::make('hire_date')
                    ->label('دامەزراندن'),
                DateRangeFilter::make('date_of_birth')
                    ->label('لەدایکبوون'),
                DateRangeFilter::make('termination_date')
                    ->label('وازهێنان'),
                Tables\Filters\SelectFilter::make('gender')
                    ->label('ڕەگەز')
                    ->options([
                        'male'=>"نێر",
                        "female"=>"مێ"
                    ])
                ->native(0),
                Tables\Filters\SelectFilter::make('currency_id')
                    ->relationship('currency', 'name')
                    ->searchable()
                    ->preload()
                    ->label('جۆری مووچە')
            ],Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
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
