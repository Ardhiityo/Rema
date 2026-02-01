<?php

use App\Models\User;
use App\Models\Author;
use App\Models\MetaData;
use App\Models\StudyProgram;
use Illuminate\Support\Str;
use function Pest\Laravel\actingAs;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

uses(RefreshDatabase::class);

test('mount edit success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataForm(['meta_data_id' => $meta_data->id])
        ->assertSet('is_update', true)
        ->assertSet('meta_data_id', $meta_data->id)
        ->assertSet('title', $meta_data->title)
        ->assertSet('year', $meta_data->year)
        ->assertSet('status', $meta_data->status)
        ->assertSet('visibility', $meta_data->visibility)
        ->assertSet('is_approve', false)
        ->assertDispatched('refresh-meta-data-session');
});

test('mount edit failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

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

    $user = User::whereEmail('author@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataForm()
        ->assertSet('is_approve', false)
        ->assertSet('author_name', $user->name)
        ->assertSet('author_nim', $user->author->nim)
        ->assertSet('author_study_program', null)
        ->assertSet('year', now()->year)
        ->assertSet('visibility', $meta_data->visibility)
        ->assertNotSet('is_update', true)
        ->assertNotSet('meta_data_id', $meta_data->id)
        ->assertNotSet('title', $meta_data->title)
        ->assertNotSet('status', $meta_data->status);
});

test('create new form success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    actingAs($user);

    metaDataForm()
        ->set('title', 'test 123')
        ->set('is_update', true)
        ->call('createNewForm')
        ->assertSet('is_update', false)
        ->assertDispatched('refresh-meta-data-session')
        ->assertNotSet('title', 'test 123');
});

test('meta data title true success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    actingAs($user);

    $component = metaDataForm()->set('is_update', true);

    expect($component->instance()->metaDataTitle())->toBe('Edit Meta Data');
});

test('meta data title false success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    actingAs($user);

    $component = metaDataForm();

    expect($component->instance()->metaDataTitle())->toBe('Create Meta Data');
});

test('create meta data admin success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('admin@gmail.com')->first();

    $author = Author::first();
    $study_program_id = StudyProgram::first()->id;

    actingAs($user);

    metaDataForm()
        ->set('title', 'tes admin 123')
        ->set('author_name', 'Arya Adhi Prasetyo')
        ->set('author_nim', 22040004)
        ->set('study_program_id', $study_program_id)
        ->set('year', 2025)
        ->set('status', 'approve')
        ->set('visibility', 'public')
        ->call('create')
        ->assertHasNoErrors()
        ->assertSet('is_update', true)
        ->assertDispatched('refresh-meta-data-session');

    $this->assertDatabaseCount('meta_data', 2);
    $this->assertDatabaseHas('meta_data', [
        'title' => 'tes admin 123',
        'author_name' => 'Arya Adhi Prasetyo',
        'author_nim' => 22040004,
        'study_program_id' => $study_program_id,
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
    $study_program_id = StudyProgram::first()->id;

    actingAs($user);

    metaDataForm()
        ->set('title', 'tes admin 123')
        ->set('author_name', 'Arya Adhi Prasetyo')
        ->set('author_nim', 22040004)
        ->set('study_program_id', $study_program_id)
        ->set('status', 'approve')
        ->set('year', '')
        ->set('visibility', 'public')
        ->call('create')
        ->assertHasErrors([
            'year' => 'required'
        ])
        ->assertSet('is_update', false)
        ->assertNotDispatched('refresh-meta-data-session');

    $this->assertDatabaseCount('meta_data', 1);
    $this->assertDatabaseMissing('meta_data', [
        'title' => 'tes admin 123',
        'author_nim' => 22040004,
        'study_program_id' => $study_program_id,
        'author_name' => 'Arya Adhi Prasetyo',
        'visibility' => 'public',
        'year' => '',
        'slug' => Str::slug('tes admin 123'),
        'status' => 'approve'
    ]);
});

test('create meta data author success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();
    $study_program_id = StudyProgram::first()->id;

    actingAs($user);

    metaDataForm()
        ->set('title', 'tes author 123')
        ->set('year', 2025)
        ->set('author_name', $user->name)
        ->set('author_nim', $user->author->nim)
        ->set('study_program_id', $study_program_id)
        ->set('status', 'approve')
        ->set('visibility', 'public')
        ->call('create')
        ->assertHasNoErrors()
        ->assertSet('is_update', true)
        ->assertDispatched('refresh-meta-data-session');

    $this->assertDatabaseCount('meta_data', 2);

    $this->assertDatabaseHas('meta_data', [
        'title' => 'tes author 123',
        'author_name' => $user->name,
        'author_nim' => $user->author->nim,
        'study_program_id' => $study_program_id,
        'visibility' => 'private',
        'status' => 'process',
        'year' => 2025,
        'slug' => Str::slug('tes author 123')
    ]);
});

test('create meta data author failed validation', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();
    $study_program_id = StudyProgram::first()->id;

    actingAs($user);

    metaDataForm()
        ->set('title', 'tes author 123')
        ->set('year', '')
        ->set('author_name', $user->name)
        ->set('author_nim', $user->author->nim)
        ->set('study_program_id', $study_program_id)
        ->call('create')
        ->assertHasErrors([
            'year' => 'required'
        ])
        ->assertSet('is_update', false)
        ->assertNotDispatched('refresh-meta-data-session');

    $this->assertDatabaseCount('meta_data', 1);

    $this->assertDatabaseMissing('meta_data', [
        'title' => 'tes author 123',
        'author_name' => $user->name,
        'author_nim' => $user->author->nim,
        'study_program_id' => $study_program_id,
        'visibility' => 'private',
        'year' => 2025,
        'slug' => Str::slug('tes author 123'),
        'status' => 'process'
    ]);
});

test('update meta data admin success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('admin@gmail.com')->first();

    $author = Author::first();
    $study_program_id = StudyProgram::first()->id;

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataForm(['meta_data_id' => $meta_data->id])
        ->set('meta_data_id', $meta_data->id)
        ->set('title', 'tes update admin 123')
        ->set('year', 2026)
        ->set('author_name', $author->user->name)
        ->set('author_nim', $author->user->author->nim)
        ->set('study_program_id', $study_program_id)
        ->set('status', 'approve')
        ->set('visibility', 'public')
        ->call('update')
        ->assertHasNoErrors()
        ->assertSet('is_update', true);

    $this->assertDatabaseCount('meta_data', 1);
    $this->assertDatabaseHas('meta_data', [
        'title' => 'tes update admin 123',
        'author_name' => $author->user->name,
        'author_nim' => $author->user->author->nim,
        'study_program_id' => $study_program_id,
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
    $study_program_id = StudyProgram::first()->id;

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataForm(['meta_data_id' => $meta_data->id])
        ->set('meta_data_id', $meta_data->id)
        ->set('title', 'tes update admin 123')
        ->set('year', '')
        ->set('author_name', 'Budiyanto')
        ->set('author_nim', '22040004')
        ->set('study_program_id', $study_program_id)
        ->set('status', 'approve')
        ->set('visibility', 'public')
        ->call('update')
        ->assertHasErrors([
            'year' => 'required'
        ])
        ->assertSet('is_update', true);

    $this->assertDatabaseCount('meta_data', 1);
    $this->assertDatabaseMissing('meta_data', [
        'title' => 'tes update admin 123',
        'author_name' => 'Budiyanto',
        'author_nim' => '22040004',
        'study_program_id' => $study_program_id,
        'visibility' => 'public',
        'year' => '',
        'slug' => Str::slug('tes update admin 123'),
        'status' => 'approve'
    ]);
});

test('update meta data author success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataForm(['meta_data_id' => $meta_data->id])
        ->set('meta_data_id', $meta_data->id)
        ->set('title', 'tes update author 123')
        ->set('year', 2027)
        ->call('update')
        ->assertHasNoErrors()
        ->assertSet('is_update', true);

    $this->assertDatabaseCount('meta_data', 1);

    $this->assertDatabaseHas('meta_data', [
        'title' => 'tes update author 123',
        'author_name' => $meta_data->author_name,
        'author_nim' => $meta_data->author_nim,
        'study_program_id' => $meta_data->study_program_id,
        'visibility' => $meta_data->visibility,
        'year' => 2027,
        'slug' => Str::slug('tes update author 123'),
        'status' => $meta_data->status
    ]);
});

test('update meta data author failed validation', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('author@gmail.com')->first();

    actingAs($user);

    $meta_data = MetaData::first();

    metaDataForm(['meta_data_id' => $meta_data->id])
        ->set('meta_data_id', $meta_data->id)
        ->set('title', 'tes update author 123')
        ->set('year', '')
        ->call('update')
        ->assertHasErrors([
            'year' => 'required'
        ])
        ->assertSet('is_update', true);

    $this->assertDatabaseCount('meta_data', 1);

    $this->assertDatabaseMissing('meta_data', [
        'title' => 'tes update author 123',
        'year' => '',
    ]);
});

test('reset input success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('admin@gmail.com')->first();

    actingAs($user);

    metaDataForm()
        ->set('title', 'Hello world')
        ->set('status', 'approve')
        ->set('visibility', 'public')
        ->call('resetInput')
        ->assertNotSet('title', 'Hello world')
        ->assertNotSet('status', 'approve')
        ->assertNotSet('visibility', 'public');
});
