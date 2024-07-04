<?php

namespace App\Filament\Resources\Finance\ExpenseResource\Widgets;

use App\Filament\Resources\Finance\ExpenseResource\Pages\ManageExpenses;
use App\Filament\Resources\Finance\StudentPaymentResource\Pages\ManageStudentPayments;
use App\Models\Settings\Currency;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ExpensesState extends BaseWidget
{
    use InteractsWithPageTable;
    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ManageExpenses::class;
    }

    protected function getStats(): array
    {
        $stats = [];
        foreach (Currency::all() as $currency) {
            $stats[]=  Stat::make($currency->name, number_format($this->getPageTableQuery()->where('currency_id',$currency->id)->sum('amount'),$currency->decimal_places))
                ->description($currency->symbol)
                ->color('success')
                ->chart($this->getPageTableQuery()->where('currency_id',$currency->id)->pluck('amount')->toArray());

        }
        return $stats;
    }
}
