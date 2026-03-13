<?php

namespace App\Filament\Clusters\EmployeeManagement\Pages;

use App\Filament\Clusters\EmployeeManagement\EmployeeManagementCluster;
use BackedEnum;
use Filament\Pages\Page;

class EmployeeReport extends Page
{
    protected static ?string $cluster = EmployeeManagementCluster::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.clusters.employee-management.pages.employee-report';

    protected static ?string $navigationLabel = 'Reports';

    protected static ?string $title = 'Employee Reports';
}
