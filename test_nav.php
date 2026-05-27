<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$request = \Illuminate\Http\Request::create('/admin/user-management/users', 'GET');
$app->instance('request', $request);

$user = \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'super_admin'); })->first();
\Illuminate\Support\Facades\Auth::login($user);

$panel = \Filament\Facades\Filament::getPanel('admin');
\Filament\Facades\Filament::setCurrentPanel($panel);
$panel->boot();

$items = \App\Filament\Clusters\UserManagement\UserManagementCluster::getNavigationItems();
foreach ($items as $item) {
    echo "- Label: " . $item->getLabel() . ", Group: " . $item->getGroup() . ", Sort: " . $item->getSort() . PHP_EOL;
}
