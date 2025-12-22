<?php

use App\Models\User;
use App\Models\Author;
use App\Models\MetaData;
use Illuminate\Support\Str;
use App\Data\Author\AuthorListData;
use function Pest\Laravel\actingAs;
use Database\Seeders\DatabaseSeeder;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

uses(RefreshDatabase::class);

test('mount edit success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataForm(['meta_data_id' => $meta_data->id])
        ->assertSet('is_update', true)
        ->assertSet('meta_data_id', $meta_data->id)
        ->assertSet('title', $meta_data->title)
        ->assertSet('year', $meta_data->year)
        ->assertSet('author_id', $meta_data->author_id)
        ->assertSet('status', $meta_data->status)
        ->assertSet('visibility', $meta_data->visibility)
        ->assertSet('is_approve', false)
        ->assertSet('keyword', $meta_data->author->nim . ' - ' . $meta_data->author->user->name)
        ->assertDispatched('refresh-meta-data-session');
});

test('mount edit failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    $this->expectException(ModelNotFoundException::class);

    metaDataForm(['meta_data_id' => 100])
        ->assertNotSet('is_update', true)
        ->assertNotSet('meta_data_id', $meta_data->id)
        ->assertNotSet('title', $meta_data->title)
        ->assertNotSet('year', $meta_data->year)
        ->assertNotSet('author_id', $meta_data->author_id)
        ->assertNotSet('status', $meta_data->status)
        ->assertNotSet('visibility', $meta_data->visibility)
        ->assertSet('is_approve', false)
        ->assertNotSet('keyword', $meta_data->author->nim . ' - ' . $meta_data->author->user->name);
});

test('mount create success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataForm()
        ->assertSet('is_approve', false)
        ->assertSet('keyword', '')
        ->assertSet('year', $meta_data->year)
        ->assertNotSet('is_update', true)
        ->assertNotSet('meta_data_id', $meta_data->id)
        ->assertNotSet('title', $meta_data->title)
        ->assertNotSet('author_id', $meta_data->author_id)
        ->assertNotSet('status', $meta_data->status)
        ->assertNotSet('visibility', $meta_data->visibility);
});

test('create new form success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    metaDataForm()
        ->set('title', 'test 123')
        ->set('is_update', true)
        ->call('createNewForm')
        ->assertSet('is_update', false)
        ->assertDispatched('refresh-meta-data-session')
        ->assertNotSet('title', 'test 123');
});

test('is lock form success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $component = metaDataForm();

    expect($component->instance()->isLockForm())
        ->toBe(false);
});

test('meta data title true success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $component = metaDataForm()->set('is_update', true);

    expect($component->instance()->metaDataTitle())->toBe('Edit Meta Data');
});

test('meta data title false success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $component = metaDataForm();

    expect($component->instance()->metaDataTitle())->toBe('Create Meta Data');
});

test('updated author id success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataForm(['meta_data_id' => $meta_data->id])
        ->assertSet('keyword', $meta_data->author->nim . ' - ' . $meta_data->author->user->name);
});

test('get authors without keyword', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('admin@gmail.com')->first();

    actingAs($user);

    $component = metaDataForm();

    expect($component->instance()->authors())
        ->toEqual(AuthorListData::collect([], DataCollection::class));
});

test('get authors with keyword', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('admin@gmail.com')->first();

    actingAs($user);

    $component = metaDataForm()
        ->set('keyword', 22040004);

    expect($component->instance()->authors())->toBeInstanceOf(DataCollection::class);

    expect(count($component->instance()->authors()))->toBe(1);
});

test('create meta data admin success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('admin@gmail.com')->first();

    $author = Author::first();

    actingAs($user);

    metaDataForm()
        ->set('title', 'tes admin 123')
        ->set('year', 2025)
        ->set('author_id', $author->id)
        ->set('status', 'approve')
        ->set('visibility', 'public')
        ->call('createMetaData')
        ->assertHasNoErrors()
        ->assertSet('is_update', true)
        ->assertDispatched('refresh-meta-data-session');

    $this->assertDatabaseCount('meta_data', 2);
    $this->assertDatabaseHas('meta_data', [
        'title' => 'tes admin 123',
        'author_id' => $author->id,
        'visibility' => 'public',
        'year' => 2025,
        'slug' => Str::slug('tes admin 123'),
        'status' => 'approve'
    ]);
});

test('create meta data admin failed validation', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('admin@gmail.com')->first();

    $author = Author::first();

    actingAs($user);

    metaDataForm()
        ->set('title', 'tes admin 123')
        ->set('author_id', $author->id)
        ->set('status', 'approve')
        ->set('year', '')
        ->set('visibility', 'public')
        ->call('createMetaData')
        ->assertHasErrors([
            'year' => 'required'
        ])
        ->assertSet('is_update', false)
        ->assertNotDispatched('refresh-meta-data-session');

    $this->assertDatabaseCount('meta_data', 1);
    $this->assertDatabaseMissing('meta_data', [
        'title' => 'tes admin 123',
        'author_id' => $author->id,
        'visibility' => 'public',
        'year' => 2025,
        'slug' => Str::slug('tes admin 123'),
        'status' => 'approve'
    ]);
});

test('create meta data contributor success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    metaDataForm()
        ->set('title', 'tes contributor 123')
        ->set('year', 2025)
        ->call('createMetaData')
        ->assertHasNoErrors()
        ->assertSet('is_update', true)
        ->assertDispatched('refresh-meta-data-session');

    $this->assertDatabaseCount('meta_data', 2);
    $this->assertDatabaseHas('meta_data', [
        'title' => 'tes contributor 123',
        'author_id' => $user->author->id,
        'visibility' => 'private',
        'year' => 2025,
        'slug' => Str::slug('tes contributor 123'),
        'status' => 'pending'
    ]);
});

test('create meta data contributor failed validation', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    metaDataForm()
        ->set('title', 'tes contributor 123')
        ->set('year', '')
        ->call('createMetaData')
        ->assertHasErrors([
            'year' => 'required'
        ])
        ->assertSet('is_update', false)
        ->assertNotDispatched('refresh-meta-data-session');

    $this->assertDatabaseCount('meta_data', 1);
    $this->assertDatabaseMissing('meta_data', [
        'title' => 'tes contributor 123',
        'author_id' => $user->author->id,
        'visibility' => 'private',
        'year' => 2025,
        'slug' => Str::slug('tes contributor 123'),
        'status' => 'pending'
    ]);
});

test('update meta data admin success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('admin@gmail.com')->first();

    $author = Author::first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataForm(['meta_data_id' => $meta_data->id])
        ->set('meta_data_id', $meta_data->id)
        ->set('title', 'tes update admin 123')
        ->set('year', 2026)
        ->set('author_id', $author->id)
        ->set('status', 'approve')
        ->set('visibility', 'public')
        ->call('updateMetaData')
        ->assertHasNoErrors()
        ->assertSet('is_update', true);

    $this->assertDatabaseCount('meta_data', 1);
    $this->assertDatabaseHas('meta_data', [
        'title' => 'tes update admin 123',
        'author_id' => $author->id,
        'visibility' => 'public',
        'year' => 2026,
        'slug' => Str::slug('tes update admin 123'),
        'status' => 'approve'
    ]);
});

test('update meta data admin failed validation', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('admin@gmail.com')->first();

    $author = Author::first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataForm(['meta_data_id' => $meta_data->id])
        ->set('meta_data_id', $meta_data->id)
        ->set('title', 'tes update admin 123')
        ->set('year', '')
        ->set('author_id', $author->id)
        ->set('status', 'approve')
        ->set('visibility', 'public')
        ->call('updateMetaData')
        ->assertHasErrors([
            'year' => 'required'
        ])
        ->assertSet('is_update', true);

    $this->assertDatabaseCount('meta_data', 1);
    $this->assertDatabaseMissing('meta_data', [
        'title' => 'tes update admin 123',
        'author_id' => $author->id,
        'visibility' => 'public',
        'year' => 2026,
        'slug' => Str::slug('tes update admin 123'),
        'status' => 'approve'
    ]);
});

test('update meta data contributor success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataForm(['meta_data_id' => $meta_data->id])
        ->set('meta_data_id', $meta_data->id)
        ->set('title', 'tes update contributor 123')
        ->set('year', 2027)
        ->call('updateMetaData')
        ->assertHasNoErrors()
        ->assertSet('is_update', true);

    $this->assertDatabaseCount('meta_data', 1);
    $this->assertDatabaseHas('meta_data', [
        'title' => 'tes update contributor 123',
        'author_id' => $user->author->id,
        'visibility' => 'private',
        'year' => 2027,
        'slug' => Str::slug('tes update contributor 123'),
        'status' => 'pending'
    ]);
});

test('update meta data contributor failed validation', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataForm(['meta_data_id' => $meta_data->id])
        ->set('meta_data_id', $meta_data->id)
        ->set('title', 'tes update contributor 123')
        ->set('year', '')
        ->call('updateMetaData')
        ->assertHasErrors([
            'year' => 'required'
        ])
        ->assertSet('is_update', true);

    $this->assertDatabaseCount('meta_data', 1);
    $this->assertDatabaseMissing('meta_data', [
        'title' => 'tes update contributor 123',
        'author_id' => $user->author->id,
        'visibility' => 'private',
        'year' => 2026,
        'slug' => Str::slug('tes update contributor 123'),
        'status' => 'pending'
    ]);
});

test('reset input success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('admin@gmail.com')->first();

    actingAs($user);

    metaDataForm()
        ->set('title', 'Hello world')
        ->set('author_id', Author::first()->id)
        ->set('status', 'approve')
        ->set('visibility', 'public')
        ->set('keyword', 22040004)
        ->call('resetInput')
        ->assertNotSet('title', 'Hello world')
        ->assertNotSet('author_id', 1)
        ->assertNotSet('status', 'approve')
        ->assertNotSet('visibility', 'public')
        ->assertNotSet('keyword', 22040004);
});
