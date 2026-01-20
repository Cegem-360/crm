<x-filament-panels::page>
    <x-filament-widgets::widgets
        :widgets="$this->getVisibleHeaderWidgets()"
        :columns="$this->getHeaderWidgetsColumns()"
        :data="$this->getWidgetData()"
    />

    <x-filament-widgets::widgets
        :widgets="$this->getVisibleFooterWidgets()"
        :columns="$this->getFooterWidgetsColumns()"
        :data="$this->getWidgetData()"
    />
</x-filament-panels::page>
