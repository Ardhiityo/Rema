<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\AvatarGenerator;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'leader']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'author']);
        Role::create(['name' => 'staff']);

        $user = User::create([
            'name' => 'Leader',
            'email' => 'leader@gmail.com',
            'password' => '@Secret123',
            'avatar' => AvatarGenerator::generate(),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('leader');

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => '@Secret123',
            'avatar' => AvatarGenerator::generate(),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('admin');

        $user = User::create([
            'name' => 'Staff',
            'email' => 'staff@gmail.com',
            'password' => '@Secret123',
            'avatar' => AvatarGenerator::generate(),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('staff');

        $user = User::create([
            'name' => 'John doe',
            'email' => 'author@gmail.com',
            'password' => '@Secret123',
            'avatar' => AvatarGenerator::generate(),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('author');

        $user->author()->create([
            'nim' => 22040004,
            // 'study_program_id' => StudyProgram::first()->id
        ]);
    }
}
