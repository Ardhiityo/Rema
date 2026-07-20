<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // For Production
        // $this->call([
        //     RolePermissionSeeder::class
        // ]);

        // For Testing
        $this->call([
            FacultySeeder::class,
            StudyProgramSeeder::class,
            RolePermissionSeeder::class,
            CategorySeeder::class,
            MetaDataSeeder::class,
            ActivitySeeder::class,
            CoordinatorSeeder::class,
            NoteSeeder::class,
        ]);
    }
}
