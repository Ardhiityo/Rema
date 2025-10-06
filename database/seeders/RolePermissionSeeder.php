<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    public function avatarGenerator($public_path)
    {
        $sourcePath = public_path($public_path);
        $fileContents = File::get($sourcePath);

        // Buat nama file unik dengan ekstensi asli
        $filename = 'avatars/' . uniqid() . '.jpg';

        // Simpan ke storage/app/public
        Storage::disk('public')->put($filename, $fileContents);

        return $filename;
    }

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
            'avatar' => $this->avatarGenerator('assets/compiled/jpg/3.jpg'),
            'email_verified_at' => now()
        ]);

        $user->assignRole('admin');

        $user = User::create([
            'name' => 'John doe',
            'email' => 'contributor@gmail.com',
            'password' => 'rahasia',
            'avatar' => $this->avatarGenerator('assets/compiled/jpg/4.jpg'),
            'email_verified_at' => now()
        ]);

        $user->assignRole('contributor');

        $user->author()->create();
    }
}
