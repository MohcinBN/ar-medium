<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleSuperAdmin = Role::create(['name' => 'SuperAdmin']);
        $permissionManageUsers = Permission::create(['name' => 'Manage users']);

        $roleSuperAdmin->givePermissionTo($permissionManageUsers);

        $user = User::find(1);
        $user->assignRole($roleSuperAdmin);
    }
}
