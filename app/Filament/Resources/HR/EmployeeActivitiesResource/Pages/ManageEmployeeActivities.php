<?php

namespace App\Filament\Resources\HR\EmployeeActivitiesResource\Pages;

use App\Filament\Resources\HR\EmployeeActivitiesResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEmployeeActivities extends ManageRecords
{
    protected static string $resource = EmployeeActivitiesResource::class;

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
