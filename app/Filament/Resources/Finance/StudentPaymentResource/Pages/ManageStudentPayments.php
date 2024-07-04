<?php

namespace App\Filament\Resources\Finance\StudentPaymentResource\Pages;

use App\Filament\Resources\Finance\StudentPaymentResource;
use App\Filament\Resources\Finance\StudentPaymentResource\Widgets\StudentPaymentWidget;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ManageRecords;

class ManageStudentPayments extends ManageRecords
{
    protected static string $resource = StudentPaymentResource::class;

    use ExposesTableToWidgets;

    protected function getFooterWidgets(): array
    {
        return [
            StudentPaymentWidget::class
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
