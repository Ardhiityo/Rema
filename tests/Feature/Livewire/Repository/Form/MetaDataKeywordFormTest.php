<?php

use App\Models\Keyword;
use App\Models\Metadata;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);
    $this->user = User::whereEmail('author@gmail.com')->first();
    $this->actingAs($this->user);
    $this->meta_data = Metadata::first();
});

test('mount success', function () {
    metaDataKeywordForm(['meta_data_id' => $this->meta_data->id])
        ->assertSet('meta_data_id', $this->meta_data->id);
});

test('mount from session success', function () {
    Session::put('meta_data', $this->meta_data);

    metaDataKeywordForm(['meta_data_id' => null])
        ->assertSet('meta_data_id', $this->meta_data->id);
});

test('validation required fields', function () {
    metaDataKeywordForm(['meta_data_id' => $this->meta_data->id])
        ->set('name', '')
        ->call('create')
        ->assertHasErrors(['name' => 'required']);
});

test('validation slug length', function () {
    metaDataKeywordForm(['meta_data_id' => $this->meta_data->id])
        ->set('name', 'ab') // slug auto set to 'ab', length 2 (min 3)
        ->call('create')
        ->assertHasErrors(['slug' => 'min']);

    metaDataKeywordForm(['meta_data_id' => $this->meta_data->id])
        ->set('name', 'this name is way too long for a slug') // slug length > 12
        ->call('create')
        ->assertHasErrors(['slug' => 'max']);
});

test('validation keyword limit', function () {
    // Already has 1 keyword from seeder (presumably)
    // Let's create 2 more to hit the limit (3)
    $this->meta_data->keywords()->createMany([
        ['name' => 'Keyword 2', 'slug' => 'keyword-2'],
        ['name' => 'Keyword 3', 'slug' => 'keyword-3'],
    ]);

    metaDataKeywordForm(['meta_data_id' => $this->meta_data->id])
        ->set('name', 'Keyword 4')
        ->call('create')
        ->assertHasErrors(['slug']); // Slug validation fails due to MetDataKeywordCreateRule
});

test('create keyword success', function () {
    metaDataKeywordForm(['meta_data_id' => $this->meta_data->id])
        ->set('name', 'New Keyword')
        ->call('create')
        ->assertHasNoErrors()
        ->assertSet('name', '') // resetInput called
        ->assertDispatched('refresh-meta-data-keyword');

    expect(Keyword::where('name', 'New keyword')->exists())->toBeTrue();
});

test('edit keyword success', function () {
    $keyword = $this->meta_data->keywords()->first();

    metaDataKeywordForm(['meta_data_id' => $this->meta_data->id])
        ->call('edit', $keyword->slug)
        ->assertSet('keyword_id', $keyword->id)
        ->assertSet('name', $keyword->name)
        ->assertSet('is_update', true);
});

test('update keyword success', function () {
    $keyword = $this->meta_data->keywords()->first();

    metaDataKeywordForm(['meta_data_id' => $this->meta_data->id])
        ->set('keyword_id', $keyword->id) // Manual set ID for update context
        ->set('is_update', true)
        ->set('name', 'Updated Name')
        ->call('update')
        ->assertHasNoErrors()
        ->assertSet('is_update', false)
        ->assertDispatched('refresh-meta-data-keyword');

    expect($keyword->refresh()->name)->toBe('Updated name');
});

test('delete keyword process success', function () {
    $keyword = $this->meta_data->keywords()->first();

    metaDataKeywordForm(['meta_data_id' => $this->meta_data->id])
        ->call('deleteConfirm', $keyword->meta_data_id, $keyword->slug)
        ->assertSet('keyword_id', $keyword->id)
        ->call('delete')
        ->assertDispatched('refresh-meta-data-keyword');

    expect(Keyword::find($keyword->id))->toBeNull();
});