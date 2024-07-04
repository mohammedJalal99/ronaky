<?php

namespace App\Filament\Resources\Finance\ExpenseTypeResource\Pages;

use App\Filament\Resources\Finance\ExpenseTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageExpenseTypes extends ManageRecords
{
    protected static string $resource = ExpenseTypeResource::class;

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
