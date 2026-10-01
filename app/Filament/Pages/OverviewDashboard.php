<?php

namespace App\Filament\Pages;

use App\Traits\HasEnterprisePermissions;
use Filament\Pages\Page;

class OverviewDashboard extends Page
{
    use HasEnterprisePermissions;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $cluster = \App\Filament\Clusters\InventoryManagement\InventoryManagementCluster::class;

    protected static ?string $navigationLabel = 'Overview';

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = -1;

    protected string $view = 'filament.pages.overview-dashboard';
}
