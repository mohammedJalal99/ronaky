<?php

namespace App\Filament\Resources\Student\DriverResource\Pages;

use App\Filament\Resources\Student\DriverResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDrivers extends ManageRecords
{
    protected static string $resource = DriverResource::class;

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
