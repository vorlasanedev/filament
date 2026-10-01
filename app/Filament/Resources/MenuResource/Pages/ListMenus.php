<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use App\Models\Menu;
use App\Services\MenuSyncService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Spatie\Permission\Models\Permission;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sync_menus')
                ->label('Sync Menus')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->action(function (MenuSyncService $service) {
                    $results = $service->sync('admin');

                    Notification::make()
                        ->title('Menus Synchronized')
                        ->body("Successfully synchronized " . count($results) . " components and permissions.")
                        ->success()
                        ->send();
                }),

            Action::make('regenerate_permissions')
                ->label('Regenerate Permissions')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->action(function () {
                    $menus = Menu::all();
                    $count = 0;

                    foreach ($menus as $menu) {
                        Permission::firstOrCreate([
                            'name' => $menu->permission_name,
                            'guard_name' => 'web',
                        ]);
                        $count++;
                    }

                    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

                    Notification::make()
                        ->title('Permissions Regenerated')
                        ->body("Verified and regenerated {$count} permissions successfully.")
                        ->success()
                        ->send();
                }),
        ];
    }
}
