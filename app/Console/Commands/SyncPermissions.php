<?php

namespace App\Console\Commands;

use App\Enums\Permissions;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class SyncPermissions extends Command
{
    protected $signature = 'permissions:sync {--guard=web} {--fresh : Delete permissions not found in the enum}';

    protected $description = 'Sync permissions from Permissions enum to database';

    public function handle(): int
    {
        $guard = $this->option('guard');

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect(Permissions::cases())
            ->map(fn (Permissions $permission) => $permission->value);

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guard,
            ]);
        }

        if ($this->option('fresh')) {
            Permission::query()
                ->where('guard_name', $guard)
                ->whereNotIn('name', $permissions)
                ->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->info("Synced {$permissions->count()} permissions.");

        return self::SUCCESS;
    }
}