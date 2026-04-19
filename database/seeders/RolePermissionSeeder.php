<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::firstOrCreate(['name' => 'post.create', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'post.delete', 'guard_name' => 'api']);

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);

        $admin = Role::findByName('admin', 'api');
        $admin->syncPermissions(['post.create', 'post.delete']);

        $user = Role::findByName('user', 'api');
        $user->syncPermissions(['post.create']);

        $adminUser = User::firstOrCreate(['email' => 'admin@test.com'], [
            'name' => 'Admin',
            'password' => bcrypt('12345678'),
        ]);
        $adminUser->syncRoles([Role::findByName('admin', 'api')]);

        $normalUser = User::firstOrCreate(['email' => 'user@test.com'], [
            'name' => 'User',
            'password' => bcrypt('12345678'),
        ]);
        $normalUser->syncRoles([Role::findByName('user', 'api')]);
    }
}
