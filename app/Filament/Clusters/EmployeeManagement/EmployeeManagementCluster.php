<?php

namespace App\Filament\Clusters\EmployeeManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Filament\Pages\Enums\SubNavigationPosition;

class EmployeeManagementCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Employees';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
}
