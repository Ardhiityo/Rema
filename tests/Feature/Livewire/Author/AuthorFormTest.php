<?php

use App\Models\Author;
use App\Models\StudyProgram;
use Illuminate\Http\UploadedFile;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('form title success', function () {
    $component = authorForm()
        ->set('is_update', true);

    expect($component->formTitle)->toBe('Edit Author');

    $component = authorForm()
        ->set('is_update', false);

    expect($component->formTitle)->toBe('Create New Author');
});

test('updated avatar success', function () {

    $image = UploadedFile::fake()->image('photo.jpg');

    authorForm()
        ->set('avatar', $image)
        ->assertSet('display_avatar', false);
});

test('create success', function () {
    $this->seed(DatabaseSeeder::class);

    $study_program = StudyProgram::first();

    $name = fake()->name();
    $nim = rand(11111111, 888888888);

    authorForm()
        ->set('name', $name)
        ->set('nim', $nim)
        ->set('study_program_id', $study_program->id)
        ->set('status', 'approve')
        ->call('create')
        ->assertHasNoErrors()
        ->assertSet('name', '')
        ->assertSet('nim', '')
        ->assertSet('study_program_id', '')
        ->assertSet('status', '')
        ->assertDispatched('refresh-authors');

    $this->assertDatabaseCount('users', 3);
    $this->assertDatabaseHas('users', [
        'name' => $name,
    ]);

    $this->assertDatabaseCount('authors', 2);
    $this->assertDatabaseHas('authors', [
        'nim' => $nim,
        'study_program_id' => $study_program->id,
        'status' => 'approve'
    ]);
});

test('create failed validation', function () {
    $this->seed(DatabaseSeeder::class);

    $study_program = StudyProgram::first();

    $name = fake()->name();

    authorForm()
        ->set('name', $name)
        ->set('study_program_id', $study_program->id)
        ->set('status', 'approve')
        ->call('create')
        ->assertHasErrors([
            'nim' => 'required'
        ])
        ->assertNotSet('name', '')
        ->assertNotSet('study_program_id', '')
        ->assertNotSet('status', '')
        ->assertNotDispatched('refresh-authors');

    $this->assertDatabaseCount('users', 2);
    $this->assertDatabaseMissing('users', [
        'name' => $name
    ]);

    $this->assertDatabaseCount('authors', 1);
    $this->assertDatabaseMissing('authors', [
        'study_program_id' => $study_program->id,
        'status' => 'approve'
    ]);
});

test('create failed validation already exists', function () {
    $this->seed(DatabaseSeeder::class);

    $study_program = StudyProgram::first();

    $name = fake()->name();
    $nim = 22040004;

    authorForm()
        ->set('name', $name)
        ->set('study_program_id', $study_program->id)
        ->set('status', 'approve')
        ->set('nim', $nim)
        ->call('create')
        ->assertHasErrors([
            'nim' => 'unique'
        ])
        ->assertNotSet('name', '')
        ->assertNotSet('nim', '')
        ->assertNotSet('study_program_id', '')
        ->assertNotSet('status', '')
        ->assertNotDispatched('refresh-authors');

    $this->assertDatabaseCount('users', 2);
    $this->assertDatabaseMissing('users', [
        'name' => $name
    ]);

    $this->assertDatabaseCount('authors', 1);
    $this->assertDatabaseMissing('authors', [
        'study_program_id' => $study_program->id,
        'status' => 'approve',
        'nim' => $nim
    ]);
});

test('edit success', function () {
    $this->seed(DatabaseSeeder::class);

    $author = Author::first();

    authorForm()
        ->call('edit', $author->id)
        ->assertSet('name', $author->user->name)
        ->assertSet('study_program_id', '')
        ->assertSet('status', 'pending')
        ->assertSet('nim', $author->nim);
});

test('edit failed failed not found', function () {
    authorForm()
        ->call('edit', 1)
        ->assertNotSet('si_update', true);
});

test('update success', function () {
    $this->seed(DatabaseSeeder::class);

    $author = Author::first();
    $study_program = StudyProgram::first();

    authorForm()
        ->set('name', 'Budi Kurniawan')
        ->set('study_program_id', $study_program->first()->id)
        ->set('status', 'approve')
        ->set('email', $author->user->email)
        ->set('nim', 22040003)
        ->set('author_id', $author->id)
        ->set('user_id', $author->user->id)
        ->call('update')
        ->assertHasNoErrors()
        ->assertSet('is_update', false)
        ->assertSet('display_avatar', false)
        ->assertSet('name', '')
        ->assertSet('study_program_id', '')
        ->assertSet('status', '')
        ->assertSet('nim', '')
        ->assertSet('author_id', $author->id)
        ->assertDispatched('refresh-authors');

    $this->assertDatabaseHas('authors', [
        'nim' => 22040003,
        'study_program_id' => $study_program->id,
        'status' => 'approve',
    ]);
    $this->assertDatabaseHas('users', [
        'name' => 'Budi Kurniawan',
        'email' => $author->user->email
    ]);
});

test('update avatar success', function () {
    $this->seed(DatabaseSeeder::class);

    $author = Author::first();
    $old_avatar = $author->user->avatar;
    $study_program = StudyProgram::first();

    authorForm()
        ->set('name', 'Budi Kurniawan')
        ->set('study_program_id', $study_program->first()->id)
        ->set('status', 'approve')
        ->set('email', $author->user->email)
        ->set('nim', 22040003)
        ->set('avatar', UploadedFile::fake()->image('avatar.jpg'))
        ->set('author_id', $author->id)
        ->set('user_id', $author->user->id)
        ->call('update')
        ->assertHasNoErrors()
        ->assertSet('is_update', false)
        ->assertSet('display_avatar', false)
        ->assertSet('name', '')
        ->assertSet('study_program_id', '')
        ->assertSet('status', '')
        ->assertSet('nim', '')
        ->assertSet('author_id', $author->id)
        ->assertDispatched('refresh-authors');

    $new_avatar = Author::first()->user->avatar;

    expect($new_avatar)->not()->toBe($old_avatar);

    $this->assertDatabaseHas('authors', [
        'nim' => 22040003,
        'study_program_id' => $study_program->id,
        'status' => 'approve',
    ]);
    $this->assertDatabaseHas('users', [
        'name' => 'Budi Kurniawan',
        'email' => $author->user->email,
        'avatar' => $new_avatar
    ]);
});

test('update failed validation', function () {
    $this->seed(DatabaseSeeder::class);

    $author = Author::first();
    $study_program = StudyProgram::first();

    authorForm()
        ->set('name', 'Budi Kurniawan')
        ->set('study_program_id', $study_program->first()->id)
        ->set('status', 'approve')
        ->set('email', $author->user->email)
        ->set('nim', '')
        ->set('author_id', $author->id)
        ->set('user_id', $author->user->id)
        ->call('update')
        ->assertHasErrors([
            'nim' => 'required'
        ])
        ->assertSet('nim', '')
        ->assertSet('author_id', $author->id)
        ->assertNotSet('name', '')
        ->assertNotSet('study_program_id', '')
        ->assertNotSet('status', '')
        ->assertNotDispatched('refresh-authors');

    $this->assertDatabaseMissing('authors', [
        'nim' => 22040003,
        'study_program_id' => $study_program->id,
        'status' => 'approve',
    ]);
    $this->assertDatabaseMissing('users', [
        'name' => 'Budi Kurniawan',
        'email' => $author->user->email
    ]);
});

test('delete confirm success', function () {
    $this->seed(DatabaseSeeder::class);

    $author = Author::first();

    authorForm()
        ->set('author_id', $author->id)
        ->call('deleteConfirm', $author->id)
        ->assertSet('author_id', $author->id);
});

test('delete confirm failed not found', function () {
    authorForm()
        ->set('author_id', 1)
        ->call('deleteConfirm', 1)
        ->assertNotSet('user_id', 1);
});

test('delete success', function () {
    $this->seed(DatabaseSeeder::class);

    $author = Author::first();

    authorForm()
        ->set('user_id', $author->user->id)
        ->assertSet('user_id', $author->user->id)
        ->call('delete')
        ->assertDispatched('refresh-authors')
        ->assertSet('display_avatar', false)
        ->assertSet('name', '')
        ->assertSet('nim', '')
        ->assertSet('study_program_id', '')
        ->assertSet('email', '')
        ->assertSet('status', '')
        ->assertSet('password', '')
        ->assertSet('avatar', '')
        ->assertSet('is_update', false);

    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseMissing('users', [
        'name' => $author->user->name,
        'email' => $author->user->email
    ]);

    $this->assertDatabaseCount('authors', 0);
    $this->assertDatabaseMissing('authors', [
        'nim' => $author->nim,
    ]);
});

test('delete failed not found', function () {
    authorForm()
        ->set('user_id', 1)
        ->call('delete')
        ->assertSet('user_id', 1)
        ->assertNotDispatched('refresh-authors');
});

test('reset input success', function () {
    authorForm()
        ->set('name', 'Arya')
        ->set('nim', 22040004)
        ->set('is_update', true)
        ->set('study_program_id', 1)
        ->call('resetInput')
        ->assertSet('name', '')
        ->assertSet('nim', '')
        ->assertSet('is_update', false)
        ->assertSet('study_program_id', '');
});
