<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'contributor']);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'rahasia'
        ])->assignRole('admin');

        $user = User::create([
            'name' => 'Contributor',
            'email' => 'contributor@gmail.com',
            'password' => 'rahasia'
        ]);

        $user->assignRole('contributor');

        $user->author()->create();
    }
}
