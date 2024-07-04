<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\StageResource\Pages;
use App\Filament\Resources\Settings\StageResource\RelationManagers;
use App\Models\Settings\Stage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StageResource extends Resource
{
    use \App\HasConfigurations;
    protected static ?int $navigationSort = 9;


    protected static ?string $model = Stage::class;
    protected static ?string $navigationIcon = 'gmdi-grading';
    protected static ?string $label = "قۆناغ";
    protected static ?string $pluralLabel = "قۆناغەکان";
    protected static ?string $navigationGroup = 'ڕێکخستنەکان';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('ناو')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('ناو')
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
            'index' => Pages\ManageStages::route('/'),
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
