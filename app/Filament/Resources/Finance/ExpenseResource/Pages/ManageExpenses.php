<?php

namespace App\Filament\Resources\Finance\ExpenseResource\Pages;

use App\Filament\Resources\Finance\ExpenseResource;
use App\Filament\Resources\Finance\StudentPaymentResource\Widgets\StudentPaymentWidget;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ManageRecords;

class ManageExpenses extends ManageRecords
{
    protected static string $resource = ExpenseResource::class;

    use ExposesTableToWidgets;

    protected function getFooterWidgets(): array
    {
        return [
            ExpenseResource\Widgets\ExpensesState::class
        ];
    }

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
