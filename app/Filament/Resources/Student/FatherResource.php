<?php

namespace App\Filament\Resources\Student;

use App\Filament\Resources\Student\FatherResource\Pages;
use App\Filament\Resources\Student\FatherResource\RelationManagers;
use App\Models\Student\Father;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FatherResource extends Resource
{
    use \App\HasConfigurations;
    protected static ?int $navigationSort = 2;


    protected static ?string $model = Father::class;

    protected static ?string $label = "باوک";
    protected static ?string $pluralLabel = "باوکان";
    protected static ?string $navigationGroup = 'پەپولەکان';

    protected static ?string $navigationIcon = 'healthicons-f-factory-worker';

    public static function form(Form $form): Form
    {
        return $form
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
                Forms\Components\TextInput::make('email')
                    ->label('پۆستەی ئەلیکترۆنی')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('work')
                    ->label('پیشە')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('ناو')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('ژمارەی مۆبایل')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('پۆستەی ئەلیکترۆنی')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('ناونیشان')
                    ->searchable(),
                Tables\Columns\TextColumn::make('work')
                    ->label('پیشە')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student_count')
                    ->label('ژمارەی پەپولەکان')
                    ->summarize(Tables\Columns\Summarizers\Summarizer::make('sum')
                        ->using(function ($query){
                            return $query->get()->sum('student_count');
                        })
                        ->numeric(0)
                        ->label('کۆی گشتی'))
                    ->numeric(0),
                Tables\Columns\TextColumn::make('male_student_count')
                    ->label('کوڕ')
                    ->summarize(Tables\Columns\Summarizers\Summarizer::make('sum')
                        ->using(function ($query){
                            return $query->get()->sum('male_student_count');
                        })
                        ->numeric(0)
                        ->label('کۆی گشتی'))
                    ->numeric(0),
                Tables\Columns\TextColumn::make('female_student_count')
                    ->label('کچ')
                    ->summarize(Tables\Columns\Summarizers\Summarizer::make('sum')
                        ->using(function ($query){
                            return $query->get()->sum('female_student_count');
                        })
                        ->numeric(0)
                        ->label('کۆی گشتی'))
                    ->numeric(0),
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
            'index' => Pages\ManageFathers::route('/'),
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
