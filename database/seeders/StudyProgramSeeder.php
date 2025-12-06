<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudyProgram::create([
            'name' => 'Teknik Informatika',
            'slug' => Str::slug('Teknik Informatika')
        ]);
        StudyProgram::create([
            'name' => 'Manajemen Informatika',
            'slug' => Str::slug('Manajemen Informatika')
        ]);
    }
}
