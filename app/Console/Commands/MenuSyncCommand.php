<?php

namespace App\Console\Commands;

use App\Services\MenuSyncService;
use Illuminate\Console\Command;

class MenuSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:sync {--panel=admin : The Filament panel ID to scan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan and dynamically synchronize all Filament menus, pages, clusters, and widgets with Spatie permissions';

    /**
     * Execute the console command.
     */
    public function handle(MenuSyncService $service): int
    {
        $panel = $this->option('panel') ?: 'admin';

        $this->info("Scanning Filament panel [{$panel}] for menus and permissions...");

        $menus = $service->sync($panel);

        $this->table(
            ['ID', 'Name', 'Type', 'Group', 'Slug', 'Permission', 'Active'],
            collect($menus)->map(function ($menu) {
                return [
                    $menu->id,
                    $menu->name,
                    $menu->type,
                    $menu->navigation_group ?: '-',
                    $menu->slug,
                    $menu->permission_name,
                    $menu->is_active ? '✅' : '❌',
                ];
            })
        );

        $this->info("Successfully synchronized " . count($menus) . " menus and permissions.");

        return Command::SUCCESS;
    }
}
