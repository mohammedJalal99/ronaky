<x-filament-panels::page class="fi-dashboard-page">
    <div class="text-4xl font-bold p-4 text-center">
      باخچەی منداڵانی ڕۆناکی ناحکومی
    </div>
    <x-filament-widgets::widgets
        :columns="$this->getColumns()"
        :data="
            [
                ...(property_exists($this, 'filters') ? ['filters' => $this->filters] : []),
                ...$this->getWidgetData(),
            ]
        "
        :widgets="$this->getVisibleWidgets()"
    />
</x-filament-panels::page>
