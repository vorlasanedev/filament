<?php

namespace App\Filament\Clusters\InventoryManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Filament\Pages\Enums\SubNavigationPosition;

class InventoryManagementCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Inventory';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
}
