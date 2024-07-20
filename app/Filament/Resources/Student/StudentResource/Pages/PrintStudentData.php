<?php

namespace App\Filament\Resources\Student\StudentResource\Pages;

use App\Filament\Resources\Student\StudentResource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Session;

class PrintStudentData extends Page
{
    protected static string $resource = StudentResource::class;

    public $students = [];
    public function mount():void
    {
        $this->students = Session::get('studentsSession');
    }
    protected static string $view = 'filament.resources.student.student-resource.pages.print-student-data';
}
