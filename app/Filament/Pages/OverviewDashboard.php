<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class OverviewDashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $cluster = \App\Filament\Clusters\InventoryManagement\InventoryManagementCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Overview';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.overview-dashboard';
}
