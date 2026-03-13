<?php

namespace App\Filament\Clusters\UserManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Filament\Pages\Enums\SubNavigationPosition;

class UserManagementCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'Users';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
}
