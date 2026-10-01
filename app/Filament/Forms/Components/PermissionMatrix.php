<?php

namespace App\Filament\Forms\Components;

use App\Services\PermissionDiscoveryService;
use Filament\Forms\Components\Field;

class PermissionMatrix extends Field
{
    protected string $view = 'filament.forms.components.permission-matrix';

    public function getDiscoveredData(): array
    {
        return app(PermissionDiscoveryService::class)->getDiscoveredPermissions();
    }

    public function getModelActions(): array
    {
        return PermissionDiscoveryService::MODEL_ACTIONS;
    }

    public function getPageActions(): array
    {
        return PermissionDiscoveryService::PAGE_ACTIONS;
    }

    public function getClusterActions(): array
    {
        return PermissionDiscoveryService::CLUSTER_ACTIONS;
    }

    public function getWidgetActions(): array
    {
        return PermissionDiscoveryService::WIDGET_ACTIONS;
    }
}
