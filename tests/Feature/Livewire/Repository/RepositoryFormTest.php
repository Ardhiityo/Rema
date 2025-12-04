<?php

use App\Models\MetaData;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('mount edit success', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    expect($meta_data->slug)->not()->toBeNull($meta_data->slug);

    repositoryForm(['meta_data_slug' => $meta_data->slug])
        ->assertSet('is_approve', false)
        ->assertSet('is_categories_empty', false)
        ->assertSet('is_update', true)
        ->assertSet('meta_data_id', $meta_data->id);
});

test('mount edit failed not found', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    repositoryForm(['meta_data_slug' => 'salah'])
        ->assertSet('is_approve', false)
        ->assertSet('is_categories_empty', false)
        ->assertNotSet('meta_data_id', $meta_data->id)
        ->assertNotSet('is_update', true);
});

test('mount create success', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    expect($meta_data->slug)->not()->toBeNull($meta_data->slug);

    repositoryForm()
        ->assertSet('is_approve', false)
        ->assertSet('is_categories_empty', false)
        ->assertNotSet('is_update', true)
        ->assertNotSet('meta_data_id', $meta_data->id);
});

test('mount create failed not found', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    expect($meta_data->slug)->not()->toBeNull($meta_data->slug);

    repositoryForm(['meta_data_slug' => 'salah'])
        ->assertSet('is_approve', false)
        ->assertSet('is_categories_empty', false)
        ->assertNotSet('is_update', true)
        ->assertNotSet('meta_data_id', $meta_data->id)
        ->assertStatus(302);
});
