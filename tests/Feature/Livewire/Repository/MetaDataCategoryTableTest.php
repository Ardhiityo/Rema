<?php

use App\Models\User;
use App\Models\MetaData;
use function Pest\Laravel\actingAs;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

uses(RefreshDatabase::class);

test('mount success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataCategoryTable([
        'meta_data_id' => $meta_data->id
    ])
        ->assertSet('meta_data_id', $meta_data->id);

    metaDataCategoryTable(['meta_data_id' => $meta_data->id])
        ->assertSet('meta_data_id', $meta_data->id);
});

test('get repositories success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    $component = metaDataCategoryTable(['meta_data_id' => $meta_data->id]);

    expect(1)->toBe(count($component->instance()->repositories()->categories));
});
