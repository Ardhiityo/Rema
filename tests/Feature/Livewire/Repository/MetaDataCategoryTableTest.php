<?php

use App\Models\User;
use App\Models\MetaData;
use function Pest\Laravel\actingAs;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('mount success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataCategoryTable(['meta_data_id' => $meta_data->id, 'is_approve' => true])
        ->assertSet('is_approve', true)
        ->assertSet('meta_data_id', $meta_data->id);

    metaDataCategoryTable(['meta_data_id' => $meta_data->id])
        ->assertSet('is_approve', false)
        ->assertSet('meta_data_id', $meta_data->id);
});

test('is lock form false contributor success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    $component = metaDataCategoryTable(['meta_data_id' => $meta_data->id])
        ->assertSet('is_approve', false)
        ->assertSet('meta_data_id', $meta_data->id);

    expect(false)
        ->toBe($component->instance()->islockForm());
});

test('is lock form true contributor success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    $component = metaDataCategoryTable(['meta_data_id' => $meta_data->id, 'is_approve' => true])
        ->assertSet('is_approve', true)
        ->assertSet('meta_data_id', $meta_data->id);

    expect(true)
        ->toBe($component->instance()->islockForm());
});

test('get repositories success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    $component = metaDataCategoryTable(['meta_data_id' => $meta_data->id]);

    expect(1)->toBe(count($component->instance()->repositories()->categories));
});

test('get repositories failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $this->expectException(ModelNotFoundException::class);

    metaDataCategoryTable(['meta_data_id' => 100]);
});
