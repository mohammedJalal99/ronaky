<?php

namespace App\Filament\Resources\Student\StudentResource\Pages;

use App\Filament\Resources\Student\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

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
