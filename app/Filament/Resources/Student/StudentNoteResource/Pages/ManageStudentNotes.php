<?php

namespace App\Filament\Resources\Student\StudentNoteResource\Pages;

use App\Filament\Resources\Student\StudentNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStudentNotes extends ManageRecords
{
    protected static string $resource = StudentNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                                        ->tooltip('F1')
                                        ->keyBindings('F1')
                                        ->icon('heroicon-o-plus')
        ];
    }
}
