<?php

namespace App\Filament\Resources\Student\MotherResource\Pages;

use App\Filament\Resources\Student\MotherResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMothers extends ManageRecords
{
    protected static string $resource = MotherResource::class;

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
