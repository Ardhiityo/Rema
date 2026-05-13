<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = fake()->unique()->company . ' Faculty';
        
        Faculty::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }
}
