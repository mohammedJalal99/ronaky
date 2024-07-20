<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Student\StudentResource;
use App\Models\Student\Student;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;
use Filament\Forms;
use Wallo\FilamentSelectify\Components\ToggleButton;

class Reports extends Page implements  HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    public $data = [];
    protected static bool $shouldRegisterNavigation = false;
    protected function getForms(): array
    {
        return[
            'employeesForm'
        ];
    }

    public function employeesForm(Form $form): Form
    {
        return $form
            ->model(Student::class)
            ->schema([
                Section::make(StudentResource::getNavigationLabel())
                    ->icon(StudentResource::getNavigationIcon())
                    ->schema([
                        DateRangePicker::make('birthdate')
                            ->label('بەرواری لەدایکبوون'),
                        DateRangePicker::make('start_date')
                            ->label('بەرواری وەرگرتن'),
                        DateRangePicker::make('graduation_date')
                            ->label('بەرواری دەرچوون'),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'male'=>"کوڕ",
                                "female"=>"کچ"
                            ])
                            ->native(0)
                            ->label('ڕەگەز'),
                        Forms\Components\Select::make('father_id')
                            ->searchable()
                            ->relationship('father','name')
                            ->label('باوک')
                            ->preload(),
                        Forms\Components\Select::make('mother_id')
                            ->searchable()
                            ->multiple()
                            ->label('دایک')
                            ->relationship('mother','name')
                            ->preload(),
                        Forms\Components\Select::make('driver_id')
                            ->searchable()
                            ->multiple()
                            ->label('شوفێر')
                            ->relationship('driver','name')
                            ->preload(),

                    ])
                ->headerActions([
                    Action::make('search')
                ])
            ])->statePath('data');
    }

    public function mount():void{
        $this->employeesForm->fill();
    }

    protected static string $view = 'filament.pages.reports';
}
