<?php

use App\Models\Category;
use App\Models\MetaData;
use Database\Seeders\CategorySeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('mount with value success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $category = Category::first();

    activity()
        ->assertSet('category', $category->slug);
});

test('mount without value success', function () {
    activity()
        ->assertSet('category', '');
});

test('reset input success', function () {
    activity()
        ->set('title', 'Tes 123')
        ->set('sort_by', 'unpopular')
        ->set('category', 'Tes 123')
        ->call('resetInput')
        ->assertSet('title', '')
        ->assertSet('sort_by', 'popular')
        ->assertSet('category', '');

    $this->seed(CategorySeeder::class);

    activity()
        ->set('title', 'Tes 123')
        ->set('sort_by', 'unpopular')
        ->set('category', 'Tes 123')
        ->call('resetInput')
        ->assertSet('title', '')
        ->assertSet('sort_by', 'popular')
        ->assertSet('category', Category::first()->slug);
});

test('render success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();

    activity()
        ->assertSeeText(Category::first()->name)
        ->assertSeeText(Category::whereSlug('journal')->first()->name)
        ->assertSeeText($meta_data->title);
});
