<?php

namespace App\Filament\Resources\Student;

use App\Filament\Resources\Settings\StageResource;
use App\Filament\Resources\Student\StudentResource\Pages;
use App\Models\Settings\Currency;
use App\Models\Student\Student;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Wallo\FilamentSelectify\Components\ToggleButton;

class StudentResource extends Resource
{
    use \App\HasConfigurations;
    use \App\HasCreateAnother;
    protected static ?string $model = Student::class;
    protected static ?int $navigationSort = 4;


    protected static ?string $navigationIcon = 'fas-graduation-cap';
    protected static ?string $pluralLabel = "پەپولەکان";
    protected static ?string $label = "پەپولە";
    protected static ?string $navigationGroup = 'پەپولەکان';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('student_details')
                    ->heading('زانییاری کەسی')
                    ->columnSpan(1)
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('وێنە')
                            ->avatar()
                            ->columnSpanFull()
                            ->image(),
                        Forms\Components\TextInput::make('name')
                            ->label('ناو')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('birthdate')
                            ->label('بەرواری لەدایکبوون')
                            ->required(),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'male'=>"کوڕ",
                                "female"=>"کچ"
                            ])
                            ->default('male')
                            ->native(0)
                            ->label('ڕەگەز')
                            ->required(),
                        Forms\Components\TextInput::make('address')
                            ->label('ناونیشان')
                            ->maxLength(255)
                            ->default(null),
                        static::selectField('father_id',FatherResource::class)
                            ->label('باوک')
                            ->relationship('father', 'name'),
                        static::selectField('mother_id',FatherResource::class)
                            ->label('دایک')
                            ->relationship('mother', 'name'),
                    ]),
                Forms\Components\Section::make('تایبەت بە باخچە')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->label('کۆد')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        static::selectField('stage_id',StageResource::class)
                            ->relationship('stage', 'name')
                            ->label('قۆناغ')
                            ->required()
                            ->default(1),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('بەرواری وەرگرتن')
                            ->default(now())
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\DatePicker::make('graduation_date')
                            ->columnSpanFull()
                            ->label('بەرواری دەرچوون'),
                        static::selectField('driver_id',DriverResource::class)
                            ->relationship('driver', 'name')
                            ->label('شۆفێر')
                            ->default(null),
                       Forms\Components\Group::make([
                           ToggleButton::make('book')
                               ->label('کتێب')
                               ->offColor('danger')
                               ->onColor('success')
                               ->offLabel('نەخێر')
                               ->onLabel('بەڵێ')
                               ->default(false),
                           ToggleButton::make('clothes')
                               ->label('جلوبەرگ')
                               ->offColor('danger')
                               ->onColor('success')
                               ->offLabel('نەخێر')
                               ->onLabel('بەڵێ')
                               ->default(false),
                           ToggleButton::make('check')
                               ->label('پشکنین')
                               ->offColor('danger')
                               ->onColor('success')
                               ->offLabel('نەخێر')
                               ->onLabel('بەڵێ')
                               ->default(false),

                       ])->columns(3)
                        ->columnSpanFull()
                    ])->columnSpan(1)
                ->columns(2),

                Forms\Components\Section::make('ژمێریاری')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->suffix('$')
                            ->label('بڕی دراو')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Get $get,Forms\Set $set,$state){
                                if($get('installments') != null && $get('installments') != 0){
                                    $set('per_month_amount',(number_format($state/$get('installments'),2,'.','')));
                                }
                            })
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('installments')
                            ->label('ژمارەی قستەکان')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Get $get,Forms\Set $set,$state){
                                if($get('installments') != null && $get('installments') != 0){
                                    $set('per_month_amount',(number_format($get('amount')/$get('installments'),0,'.','')));
                                }
                            })
                            ->numeric()
                            ->default(3),
                        Forms\Components\TextInput::make('per_month_amount')
                            ->suffix('$')
                            ->label('بڕی مانگانە')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Get $get,Forms\Set $set,$state){
                                    $set('amount',(number_format($get('per_month_amount') * $get('installments'),2,'.','')));
                            })
                            ->required()
                            ->numeric(),
                    ])->columns(3),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               Tables\Columns\Layout\Split::make([
                   Tables\Columns\ImageColumn::make('image')
                       ->circular()
                       ->size(60)
                       ->label('وێنە'),
                  Tables\Columns\Layout\Stack::make([
                      Tables\Columns\TextColumn::make('code')
                          ->formatStateUsing(fn($state)=>"<b>کۆد: </b> ". $state)
                          ->html()
                          ->weight(FontWeight::ExtraBold)
                          ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                          ->color(Color::Blue)
                          ->label('کۆد')
                          ->searchable(),
                      Tables\Columns\TextColumn::make('stage.name')
                          ->label('قۆناغ')
                          ->numeric()
                          ->weight(FontWeight::ExtraBold)
                          ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                          ->color(Color::Red)
                          ->formatStateUsing(fn($state)=>"<b>قۆناغ: </b> ". $state)
                          ->html()
                          ->sortable(),
                      Tables\Columns\TextColumn::make('name')
                          ->label('ناو')
                          ->weight(FontWeight::ExtraBold)
                          ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                          ->color(Color::Green)
                          ->formatStateUsing(fn($state,$record)=>"<b>ناو: </b> ". $state)
                          ->html()
                          ->searchable(),
                      Tables\Columns\TextColumn::make('amount')
                          ->label('بڕی پارە')
                          ->color(Color::Fuchsia)
                          ->suffix('$')
                          ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                          ->formatStateUsing(fn($state,$record)=>'بڕی پارە : '.number_format($state,2))
                          ->html()
                          ->sortable(),
                      Tables\Columns\TextColumn::make('due_amount')
                          ->label('بڕی پارە')
                          ->color(fn($state)=>$state>0?Color::Red:Color::Green)
                          ->suffix('$')
                          ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                          ->formatStateUsing(fn($state,$record)=>'بڕی ماوە : '.number_format($state,2))
                          ->html()
                  ])
               ]),
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('father.name')
                            ->label('باوک')
                            ->weight(FontWeight::ExtraBold)
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                            ->formatStateUsing(fn($state)=>"<b>ناوی باوک: </b> ". $state)
                            ->searchable()
                            ->html()
                            ->sortable(),
                        Tables\Columns\TextColumn::make('mother.name')
                            ->label('دایک')
                            ->searchable()
                            ->weight(FontWeight::ExtraBold)
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                            ->formatStateUsing(fn($state)=>"<b>ناوی دایک: </b> ". $state)
                            ->html()
                            ->sortable(),
                        Tables\Columns\TextColumn::make('driver.name')
                            ->label('شۆفێر')
                            ->searchable()
                            ->color(Color::Blue)
                            ->weight(FontWeight::ExtraBold)
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                            ->formatStateUsing(fn($state)=>"<b>ناوی شۆفێر: </b> ". $state)
                            ->html()
                            ->sortable(),
                        Tables\Columns\TextColumn::make('birthdate')
                            ->label('لەدایک بوون')
                            ->date('Y-m-d')
                            ->color(Color::Blue)
                            ->weight(FontWeight::ExtraBold)
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                            ->formatStateUsing(fn($state)=>"<b>بەرواری لەدایکبوون: </b> ". $state)
                            ->html()
                            ->sortable(),
                        Tables\Columns\TextColumn::make('gender')
                            ->label('ڕەگەز')
                            ->weight(FontWeight::ExtraBold)
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                            ->color(function ($state){
                                if($state == 'male'){
                                    return Color::Blue;
                                }else{
                                    return Color::Pink;
                                }
                            })
                            ->formatStateUsing(fn($state)=>"<b>ڕەگەز: </b> ".[
                                    'male'=>"کوڕ",
                                    "female"=>"کچ"
                                ][$state])
                            ->html(),
                        Tables\Columns\TextColumn::make('address')
                            ->weight(FontWeight::ExtraBold)
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                            ->formatStateUsing(fn($state)=>"<b>ناونیشان: </b> ". $state)
                            ->html()
                            ->label('ناونیشان')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('start_date')
                            ->date('Y-m-d')
                            ->weight(FontWeight::ExtraBold)
                            ->color(Color::Green)
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                            ->formatStateUsing(fn($state)=>"<b>بەرواری وەرگرتن: </b> ". $state)
                            ->html()
                            ->label('وەرگرتن')
                            ->sortable(),
                        Tables\Columns\TextColumn::make('graduation_date')
                            ->label('دەرچوون')
                            ->date('Y-m-d')
                            ->color(Color::Red)
                            ->weight(FontWeight::ExtraBold)
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                            ->formatStateUsing(fn($state)=>"<b>بەرواری دەرچوون: </b> ". $state)
                            ->html()
                            ->sortable(),
                        Tables\Columns\TextColumn::make('book')
                            ->label('کتێب')
                            ->badge()
                            ->formatStateUsing(fn($state)=>"<b>کتێب: </b> ".($state?'بەڵێ':'نەخێر'))
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                            ->html()
                            ->color(fn($state)=>$state?Color::Green:Color::Red),
                        Tables\Columns\TextColumn::make('clothes')
                            ->label('جل و بەرگ')
                            ->badge()
                            ->formatStateUsing(fn($state)=>"<b>جل و بەرگ: </b> ".($state?'بەڵێ':'نەخێر'))
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                            ->html()
                            ->color(fn($state)=>$state?Color::Green:Color::Red),
                        Tables\Columns\TextColumn::make('check')
                            ->label('پشکنین')
                            ->badge()
                            ->formatStateUsing(fn($state)=>"<b>پشکنین: </b> ".($state?'بەڵێ':'نەخێر'))
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                            ->html()
                            ->color(fn($state)=>$state?Color::Green:Color::Red),

                        Tables\Columns\TextColumn::make('installments')
                            ->numeric(0)
                            ->label('قستەکان')
                            ->color(Color::Fuchsia)
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                            ->formatStateUsing(fn($state)=>'قستەکان : '.$state)
                            ->sortable(),
                        Tables\Columns\TextColumn::make('per_month_amount')
                            ->numeric()
                            ->label('بڕی قست')
                            ->suffix(fn($record)=>$record->currency->symbol)
                            ->color(Color::Fuchsia)
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                            ->formatStateUsing(fn($state,$record)=>'بڕی قست : '.number_format($state,$record->currency->decimal_places))
                            ->html()
                            ->sortable(),
                        Tables\Columns\TextColumn::make('deleted_at')
                            ->label('سڕینەوە')
                            ->dateTime('Y-m-d')
                            ->formatStateUsing(fn($state)=>"سڕینەوە: ". $state->format('Y-m-d'))
                            ->color(Color::Red)
                            ->sortable(),
                        Tables\Columns\TextColumn::make('created_at')
                            ->label('دروستکردن')
                            ->dateTime('Y-m-d')
                            ->formatStateUsing(fn($state)=>"دروستکردن: ". $state->format('Y-m-d'))
                            ->color(Color::Green)
                            ->sortable(),
                        Tables\Columns\TextColumn::make('updated_at')
                            ->label('نوێکردنەوە')
                            ->dateTime('Y-m-d')
                            ->formatStateUsing(fn($state)=>"نوێکردنەوە : ". $state->format('Y-m-d'))
                            ->color(Color::Amber)
                            ->sortable(),
                    ])
                    ->space(2)
                ])->collapsible()

            ])->contentGrid([
                'sm'=>1,
                'lg'=>2
            ])
            ->filters([
            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('created_at')
                                ->label('دروستکردن'),
                            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('updated_at')
                                ->label('نوێکردنەوە'),
                            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('deleted_at')
                                ->label('سڕینەوە'),
                DateRangeFilter::make('start_date')
                    ->label('بەرواری وەرگرتن'),
                DateRangeFilter::make('graduation_date')
                    ->label('بەرواری دەرچوون'),
                DateRangeFilter::make('birthdate')
                    ->label('بەرواری لەدایکبوون'),
                Tables\Filters\TrashedFilter::make()->native(0),
                Tables\Filters\SelectFilter::make('father_id')
                    ->label('باوک')
                    ->relationship('father', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('mother_id')
                    ->relationship('mother', 'name')
                    ->label('دایک')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('gender')
                    ->native(0)
                    ->options([
                        'female'=>'کچ',
                       'male'=>'کوڕ'
                    ])
                ->label('ڕەگەز'),
                Tables\Filters\SelectFilter::make('driver_id')
                    ->relationship('driver', 'name')
                    ->label('شۆفێر')
                    ->searchable()
                     ->preload(),
                Tables\Filters\SelectFilter::make('stage_id')
                    ->relationship('stage', 'name')
                    ->label('قۆناغ')
                    ->searchable()
                    ->preload(),

            ],Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\Action::make('father_detail')
                        ->label('زانییارییەکانی باوک')
                        ->icon(FatherResource::getNavigationIcon())
                        ->form(function ($record){
                           return [
                               Forms\Components\TextInput::make('name')
                                   ->default($record->father->name)
                                   ->label('ناو')
                                   ->disabled()
                                   ->required()
                                   ->maxLength(255),
                               Forms\Components\TextInput::make('phone')
                                   ->label('ژمارەی مۆبایل')
                                   ->disabled()
                                   ->default($record->father->phone)
                                   ->tel()
                                   ->maxLength(255)
                                   ->default(null),
                               Forms\Components\TextInput::make('email')
                                   ->label('پۆستەی ئەلیکترۆنی')
                                   ->disabled()
                                   ->default($record->father->email)
                                   ->email()
                                   ->maxLength(255)
                                   ->default(null),
                               Forms\Components\TextInput::make('work')
                                   ->default($record->father->work)
                                   ->disabled()
                                   ->label('پیشە')
                                   ->maxLength(255)
                                   ->default(null),
                           ];
                        })
                        ->color(Color::Blue)
                        ->requiresConfirmation()
                        ->modalIcon(FatherResource::getNavigationIcon())
                        ->modalDescription(' ')
                        ->modalFooterActions([
                            DeleteAction::make()
                                ->hidden()
                        ]),
                    Tables\Actions\Action::make('mother_detail')
                        ->label('زانییارییەکانی دایک')
                        ->icon(MotherResource::getNavigationIcon())
                        ->requiresConfirmation()
                        ->modalIcon(MotherResource::getNavigationIcon())
                        ->modalDescription(' ')
                        ->color(Color::Green)
                        ->form(function ($record){
                            return [
                                Forms\Components\TextInput::make('name')
                                    ->default($record->mother->name)
                                    ->label('ناو')
                                    ->disabled()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->label('ژمارەی مۆبایل')
                                    ->disabled()
                                    ->default($record->mother->phone)
                                    ->tel()
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('email')
                                    ->label('پۆستەی ئەلیکترۆنی')
                                    ->disabled()
                                    ->default($record->mother->email)
                                    ->email()
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('work')
                                    ->default($record->mother->work)
                                    ->disabled()
                                    ->label('پیشە')
                                    ->maxLength(255)
                                    ->default(null),
                            ];
                        })
                        ->modalFooterActions([
                            DeleteAction::make()
                                ->hidden()
                        ])

                ])->button()
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
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
