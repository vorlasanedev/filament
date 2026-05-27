<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$request = \Illuminate\Http\Request::create('/admin', 'GET');
$app->instance('request', $request);

// Authenticate using the first user with 'super_admin' role
$user = \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'super_admin'); })->first();
if ($user) {
    \Illuminate\Support\Facades\Auth::login($user);
    echo "Logged in as: " . $user->email . PHP_EOL;
}

$panel = \Filament\Facades\Filament::getPanel('admin');
\Filament\Facades\Filament::setCurrentPanel($panel);
$panel->boot();

$userNavItems = \App\Filament\Resources\UserResource::getNavigationItems();
echo "UserResource Nav Items Count: " . count($userNavItems) . PHP_EOL;
foreach ($userNavItems as $item) {
    echo "- Label: " . $item->getLabel() . ", Is Visible: " . ($item->isVisible() ? 'Yes' : 'No') . PHP_EOL;
}
