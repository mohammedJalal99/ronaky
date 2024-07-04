<?php

namespace App\Filament\Widgets;

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
        $panel = new Panel();
        $panel = $panel
            ->id('pages')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages');
        foreach ($panel->getResources() as $resource) {
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
