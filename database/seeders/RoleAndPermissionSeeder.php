<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        Role::firstOrCreate(['name' => 'superadmin']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'manager']);
        Role::firstOrCreate(['name' => 'cashier']);
        Role::firstOrCreate(['name' => 'kitchen']);
        Role::firstOrCreate(['name' => 'waiter']);
        Role::firstOrCreate(['name' => 'customer']);

        // Define permissions (basic examples)
        Permission::firstOrCreate(['name' => 'manage restaurants']);
        Permission::firstOrCreate(['name' => 'manage branches']);
        Permission::firstOrCreate(['name' => 'manage tables']);
        Permission::firstOrCreate(['name' => 'manage menu']);
        Permission::firstOrCreate(['name' => 'view reports']);
        Permission::firstOrCreate(['name' => 'process orders']);
        Permission::firstOrCreate(['name' => 'prepare food']);
        Permission::firstOrCreate(['name' => 'serve food']);
        Permission::firstOrCreate(['name' => 'place orders']);

        // Assign permissions to roles
        $superadmin = Role::findByName('superadmin');
        $superadmin->givePermissionTo(Permission::all());

        $admin = Role::findByName('admin');
        $admin->givePermissionTo(['manage branches', 'manage tables', 'manage menu', 'view reports', 'process orders']);

        $kitchen = Role::findByName('kitchen');
        $kitchen->givePermissionTo(['prepare food']);

        $waiter = Role::findByName('waiter');
        $waiter->givePermissionTo(['serve food', 'process orders']);

        $customer = Role::findByName('customer');
        $customer->givePermissionTo(['place orders']);
    }
}
