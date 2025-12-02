<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Services\AvatarGenerator;
use Spatie\Permission\Models\Role;

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

        $user->author()->create([
            'nim' => 22040004
        ]);
    }
}
