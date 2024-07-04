<?php

namespace App\Filament\Resources\Settings\StageResource\Pages;

use App\Filament\Resources\Settings\StageResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStages extends ManageRecords
{
    protected static string $resource = StageResource::class;

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
