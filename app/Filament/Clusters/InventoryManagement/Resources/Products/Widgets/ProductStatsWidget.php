<?php

namespace App\Filament\Clusters\InventoryManagement\Resources\Products\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Product;

class ProductStatsWidget extends BaseWidget
{
    public ?Product $record = null;

    protected function getColumns(): int | array | null
    {
        return [
            'default' => 2,
            'sm' => 3,
            'md' => 4,
            'xl' => 6,
        ];
    }

    protected function getStats(): array
    {
        return [
            Stat::make('On Hand', '0.00')
                ->icon('heroicon-o-cube'),
            Stat::make('Forecasted', '0.00')
                ->icon('heroicon-o-chart-bar'),
            Stat::make('Documents', '0')
                ->icon('heroicon-o-document-text'),
            Stat::make('Sold', '0.00')
                ->icon('heroicon-o-currency-dollar'),
            Stat::make('Reordering Rules', '0')
                ->icon('heroicon-o-arrow-path'),
            Stat::make('In / Out', '0 / 0')
                ->icon('heroicon-o-arrows-right-left'),
        ];
    }
}
