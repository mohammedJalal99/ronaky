<?php

namespace App\Filament\Resources\Student\FatherResource\Pages;

use App\Filament\Resources\Student\FatherResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFathers extends ManageRecords
{
    protected static string $resource = FatherResource::class;

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
