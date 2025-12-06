<?php

namespace Database\Seeders;

use App\Models\Coordinator;
use App\Models\StudyProgram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoordinatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coordinator::create([
            'name' => 'Arya Adhi Prasetyo',
            'nidn' => 22040004,
            'position' => 'Dekan Fakultas Ilmu Komputer',
            'study_program_id' => StudyProgram::first()->id
        ]);
    }
}
