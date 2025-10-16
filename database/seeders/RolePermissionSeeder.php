<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\AvatarGenerator;
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

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'rahasia',
            'avatar' => AvatarGenerator::generate(),
            'email_verified_at' => now()
        ]);

        $user->assignRole('admin');

        $user = User::create([
            'name' => 'John doe',
            'email' => 'contributor@gmail.com',
            'password' => 'rahasia',
            'avatar' => AvatarGenerator::generate(),
            'email_verified_at' => now()
        ]);

        $user->assignRole('contributor');

        $user->author()->create();
    }
}
