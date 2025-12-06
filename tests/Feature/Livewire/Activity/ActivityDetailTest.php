<?php

use App\Livewire\ActivityDetail;
use App\Models\Activity;
use App\Models\Category;
use App\Models\MetaData;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('mount success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $category = Category::first();
    $meta_data = MetaData::first();

    Livewire::test(
        ActivityDetail::class,
        [
            'category_slug' => $category->slug,
            'meta_data_slug' => $meta_data->slug
        ]
    )
        ->assertSet('category_slug', $category->slug)
        ->assertSet('meta_data_slug', $meta_data->slug);
});

test('render success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $category = Category::first();
    $meta_data = MetaData::first();

    $activity = Activity::first();

    Livewire::test(
        ActivityDetail::class,
        [
            'category_slug' => $category->slug,
            'meta_data_slug' => $meta_data->slug
        ]
    )
        ->assertSeeText($activity->user_agent)
        ->assertSeeText($activity->ip);
});

test('render failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $activity = Activity::first();

    Livewire::test(
        ActivityDetail::class,
        [
            'category_slug' => 'salah',
            'meta_data_slug' => 'salah'
        ]
    )
        ->assertDontSeeText($activity->user_agent)
        ->assertDontSeeText($activity->ip);
});
