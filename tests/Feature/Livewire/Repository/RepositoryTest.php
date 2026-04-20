<?php

use App\Models\MetaData;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('mount edit success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    expect($meta_data->slug)->not()->toBeNull($meta_data->slug);

    repository(['meta_data_slug' => $meta_data->slug])
        ->assertSet('is_approve', false)
        ->assertSet('is_categories_empty', false)
        ->assertSet('is_update', true)
        ->assertSet('meta_data_id', $meta_data->id);
});

test('mount edit failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    repository(['meta_data_slug' => 'salah'])
        ->assertSet('is_approve', false)
        ->assertSet('is_categories_empty', false)
        ->assertNotSet('meta_data_id', $meta_data->id)
        ->assertNotSet('is_update', true);
});

test('mount create success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    expect($meta_data->slug)->not()->toBeNull($meta_data->slug);

    repository()
        ->assertSet('is_approve', false)
        ->assertSet('is_categories_empty', false)
        ->assertNotSet('is_update', true)
        ->assertNotSet('meta_data_id', $meta_data->id);
});

test('mount create failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    expect($meta_data->slug)->not()->toBeNull($meta_data->slug);

    repository(['meta_data_slug' => 'salah'])
        ->assertSet('is_approve', false)
        ->assertSet('is_categories_empty', false)
        ->assertNotSet('is_update', true)
        ->assertNotSet('meta_data_id', $meta_data->id)
        ->assertStatus(302);
});
