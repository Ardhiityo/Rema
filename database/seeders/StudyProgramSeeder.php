<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculty = Faculty::first();

        StudyProgram::create([
            'name' => 'Teknik Informatika',
            'slug' => Str::slug('Teknik Informatika'),
            'faculty_id' => $faculty->id
        ]);
        StudyProgram::create([
            'name' => 'Manajemen Informatika',
            'slug' => Str::slug('Manajemen Informatika'),
            'faculty_id' => $faculty->id
        ]);
    }
}
