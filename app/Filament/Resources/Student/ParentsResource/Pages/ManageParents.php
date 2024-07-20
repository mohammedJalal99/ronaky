<?php

namespace App\Filament\Resources\Student\ParentsResource\Pages;

use App\Filament\Resources\Student\ParentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageParents extends ManageRecords
{
    protected static string $resource = ParentsResource::class;

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
