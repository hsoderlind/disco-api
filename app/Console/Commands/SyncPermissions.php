<?php

namespace App\Console\Commands;

use App\Models\Shop;
use App\Services\Permissions\PermissionsService;
use Illuminate\Console\Command;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'disco:sync-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update role\'s permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permissions = PermissionsService::findOrCreatePermissions();

        $this->withProgressBar(Shop::all(), function (Shop $shop) use ($permissions) {
            $defaultRoles = $shop->roles->whereIn('name', [
                PermissionsService::ROLE_ADMIN,
                PermissionsService::ROLE_CASHIER,
                PermissionsService::ROLE_SUPER_ADMIN,
                PermissionsService::ROLE_WAREHOUSE_WORKER,
            ])->keyBy('name');
            PermissionsService::syncRDefaultRolesAndPermissions($defaultRoles, $permissions);
        });
    }
}
