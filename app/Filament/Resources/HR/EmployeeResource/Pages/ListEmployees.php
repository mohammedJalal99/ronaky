<?php

namespace App\Filament\Resources\HR\EmployeeResource\Pages;

use App\Filament\Resources\HR\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

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
