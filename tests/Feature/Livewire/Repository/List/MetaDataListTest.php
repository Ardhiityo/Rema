<?php

use App\Models\MetaData;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('delete confirm success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();

    $user = User::where('email', 'author@gmail.com')->first();

    actingAs($user);

    metaDataList()
        ->set('meta_data_id', $meta_data->id)
        ->call('destroyConfirm', $meta_data->slug)
        ->assertSet('meta_data_id', $meta_data->id);
});

test('delete confirm failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();

    $user = User::where('email', 'author@gmail.com')->first();

    actingAs($user);

    metaDataList()
        ->set('meta_data_id', 100)
        ->call('destroyConfirm', $meta_data->slug)
        ->assertNotSet('meta_data_id', 100);
});

test('delete success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();

    $user = User::where('email', 'author@gmail.com')->first();

    actingAs($user);

    metaDataList()
        ->set('meta_data_id', $meta_data->id)
        ->call('destroy');

    $this->assertDatabaseCount('meta_data', 0);
    $this->assertDatabaseMissing('meta_data', [
        'title' => $meta_data->title,
        'year' => $meta_data->year,
        'slug' => $meta_data->slug,
    ]);
});

test('delete failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();

    $user = User::where('email', 'author@gmail.com')->first();

    actingAs($user);

    metaDataList()
        ->set('meta_data_id', 100)
        ->call('destroy');

    $this->assertDatabaseCount('meta_data', 1);
    $this->assertDatabaseHas('meta_data', [
        'title' => $meta_data->title,
        'year' => $meta_data->year,
        'slug' => $meta_data->slug,
    ]);
});

test('reset input success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::where('email', 'author@gmail.com')->first();

    actingAs($user);

    metaDataList()
        ->set('keyword', 'tes 123')
        ->set('year', 2025)
        ->set('status_filter', 'approve')
        ->set('visibility', 'public')
        ->call('resetInput')
        ->assertSet('status_filter', 'approve')
        ->assertSet('visibility', 'public')
        ->assertNotSet('keyword', 'tes 123')
        ->assertNotSet('year', 2025);
});

test('render success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::where('email', 'author@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataList()
        ->set('status_filter', 'process')
        ->assertSeeText(Str::limit($meta_data->title, 35, '...'))
        ->assertSeeText(Str::limit($meta_data->author->user->name, 15, '...'));
});
