<?php

use App\Models\Category;
use App\Models\MetaData;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;
use function PHPUnit\Framework\assertNotNull;

uses(RefreshDatabase::class);

test('mount with value success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $category = Category::first();

    assertNotNull($category->slug);
    
    activity()
        ->assertSet('category', $category->slug);
});

test('mount without value success', function () {
    Storage::fake('public');

    $this->seed(RolePermissionSeeder::class);

    $user = User::first();

    actingAs($user);

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

    $meta_data = MetaData::first()->title;

    $category1 = Category::first()->name;
    $category2 = Category::whereSlug('journal')->first()->name;

    activity()
        ->assertSeeText($category1)
        ->assertSeeText($category2)
        ->assertSeeText(Str::limit($meta_data, 55, '...'));
});
