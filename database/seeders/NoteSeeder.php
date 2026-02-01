<?php

namespace Database\Seeders;

use App\Models\Metadata;
use App\Models\Note;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Note::create([
            'message' => fake()->sentence(),
            'meta_data_id' => Metadata::first()->id
        ]);
    }
}
