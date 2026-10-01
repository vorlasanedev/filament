<?php

namespace App\Console\Commands;

use App\Services\PermissionDiscoveryService;
use Illuminate\Console\Command;

class PermissionsSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync {--panel=admin : The Filament panel ID to scan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan Filament Resources, Models, Pages, Widgets, and Clusters to dynamically generate permissions in the Spatie database';

    /**
     * Execute the console command.
     */
    public function handle(PermissionDiscoveryService $service): int
    {
        $panel = $this->option('panel') ?: 'admin';

        $this->info("Scanning Filament panel [{$panel}], Models, Pages, and Widgets...");

        $results = $service->sync($panel);

        $this->table(
            ['Category', 'Count'],
            [
                ['Discovered Models', count($results['models'])],
                ['Discovered Pages', count($results['pages'])],
                ['Discovered Clusters', count($results['clusters'])],
                ['Discovered Widgets', count($results['widgets'])],
                ['Total Permissions', $results['total']],
                ['Newly Created Permissions', $results['created']],
                ['Existing Permissions', $results['existing']],
            ]
        );

        $this->info("Successfully synchronized all Enterprise RBAC permissions!");

        return Command::SUCCESS;
    }
}
