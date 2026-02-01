<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Metadata;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activity::create([
            'user_id' => User::first()->id,
            'ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'category_id' => Category::first()->id,
            'meta_data_id' => Metadata::first()->id
        ]);
    }
}
