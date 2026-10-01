<?php

namespace App\Filament\Pages;

use App\Traits\HasEnterprisePermissions;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    use HasEnterprisePermissions;
    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}
