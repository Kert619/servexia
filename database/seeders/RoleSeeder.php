<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $resources = [
            'users',
            'companies',
        ];

        $actions = [
            'create',
            'readAll',
            'read',
            'update',
            'delete',
        ];

        foreach ($resources as $resource) {
            Permission::firstOrCreate([
                'name' => "{$resource}.*",
            ]);

            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$resource}.{$action}",
                ]);
            }
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $owner      = Role::firstOrCreate(['name' => 'owner']);
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $worker     = Role::firstOrCreate(['name' => 'worker']);
        $customer   = Role::firstOrCreate(['name' => 'customer']);

        $superAdmin->syncPermissions(Permission::all());

        /**Owner Permissions */
        $owner->syncPermissions([
            'users.create',
            'users.readAll',
            'users.read',
            'users.update',
            'users.delete',
            'companies.create',
            'companies.read',
            'companies.update',
            'companies.delete',
        ]);
        /**Owner Permissions */

        /**Admin Permissions */
        $admin->syncPermissions([
            'users.create',
            'users.readAll',
            'users.read',
            'users.update',
            'users.delete',
        ]);
        /**Admin Permissions */

        /**Worker Permissions */
        $worker->syncPermissions([
            'users.read',
            'users.update',
        ]);
        /**Worker Permissions */

        /**Customer Permissions */
        $customer->syncPermissions([
            'users.read',
            'users.update',
        ]);
        /**Customer Permissions */
    }
}
