<?php

namespace App\Filament\Resources\Student;

use App\Filament\Resources\Student\ParentsResource\Pages;
use App\Filament\Resources\Student\ParentsResource\RelationManagers;
use App\Models\Student\Parents;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParentsResource extends Resource
{
    use \App\HasConfigurations;

    protected static ?string $model = Parents::class;

    protected static ?string $navigationIcon = 'fas-hands-holding-child';
    protected static ?string $label = "دایک و باوک";
    protected static ?string $pluralLabel = "دایکان و باوکان";
    protected static ?string $navigationGroup = 'پەپوولەکان';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('father_name')
                    ->label('ناوی باوک')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mother_name')
                    ->label('ناوی دایک')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('father_phone')
                    ->label('ژمارەی مۆبایلی باوک')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mother_phone')
                    ->label('ژمارەی مۆبایلی دایک')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('father_work')
                    ->label('پیشەی باوک')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('mother_work')
                    ->label('پیشەی دایک')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('father_name')
                    ->label('ناوی باوک')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mother_name')
                    ->label('ناوی دایک')
                    ->searchable(),
                Tables\Columns\TextColumn::make('father_phone')
                    ->label('ژمارەی مۆبایلی باوک')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mother_phone')
                    ->label('ژمارەی مۆبایلی دایک')
                    ->searchable(),
                Tables\Columns\TextColumn::make('father_work')
                    ->label('پیشەی باوک')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mother_work')
                    ->label('پیشەی دایک')
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
            'index' => Pages\ManageParents::route('/'),
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
