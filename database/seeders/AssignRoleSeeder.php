<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AssignRoleSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // Or User::find(1) if you know the ID
        if ($user) {
            $user->assignRole('SuperAdmin');
        }
    }
}
