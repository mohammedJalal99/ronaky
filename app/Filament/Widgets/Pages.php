<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Finance\ExpenseResource;
use App\Filament\Resources\Finance\ExpenseTypeResource;
use App\Filament\Resources\Finance\StudentPaymentResource;
use App\Filament\Resources\HR\EmployeeActivitiesResource;
use App\Filament\Resources\HR\EmployeeResource;
use App\Filament\Resources\Settings\CurrencyResource;
use App\Filament\Resources\Settings\StageResource;
use App\Filament\Resources\Settings\UserResource;
use App\Filament\Resources\Shield\RoleResource;
use App\Filament\Resources\Student\DriverResource;
use App\Filament\Resources\Student\ParentsResource;
use App\Filament\Resources\Student\StudentNoteResource;
use App\Filament\Resources\Student\StudentResource;
use App\Models\User;
use EightyNine\Reports\ReportsPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class Pages extends BaseWidget
{

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $stats = [];
        $resources = [
          StudentResource::class,
            ParentsResource::class,
            StageResource::class,
            StudentPaymentResource::class,
            CurrencyResource::class,
            EmployeeResource::class,
            EmployeeActivitiesResource::class,
            ExpenseResource::class,
            ExpenseTypeResource::class,
            RoleResource::class,
            DriverResource::class,
            UserResource::class,
            StudentNoteResource::class

        ];

        foreach ($resources as $resource) {
            $resource = new $resource();
            $stats[] = Stat::make('', $resource->getNavigationLabel())
            ->url($resource->getUrl())
            ->description($resource->getNavigationGroup())
            ->descriptionColor(Color::Green);
        }
        return $stats;
    }


    protected static string $view = 'filament.widgets.pages';
}
