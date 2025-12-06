<?php

use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use function Pest\Laravel\actingAs;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('mount admin success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::first();

    actingAs($user);

    profile()
        ->assertSet('role', 'admin')
        ->assertSet('user', $user)
        ->assertSet('name', $user->name)
        ->assertSet('email', $user->email)
        ->assertSet('display_avatar', '/storage/' . $user->avatar);
});

test('mount contributor success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    profile()
        ->assertSet('role', 'contributor')
        ->assertSet('user', $user)
        ->assertSet('author', $user->author)
        ->assertSet('name', $user->name)
        ->assertSet('email', $user->email)
        ->assertSet('nim', $user->author->nim)
        ->assertSet('status', $user->author->status)
        ->assertSet('study_program_id', $user->author->study_program_id)
        ->assertSet('display_avatar', '/storage/' . $user->avatar);
});

test('updated avatar success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);
    Storage::fake('public');

    profile()
        ->set(
            'avatar',
            UploadedFile::fake()->image('tes.jpg')
        )
        ->assertSet('diplay_avatar', false);
});

test('is lock form success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    $component = profile();

    expect(false)
        ->toBe($component->instance()->isLockForm());


    $user->author()->update([
        'status' => 'approve'
    ]);

    actingAs($user);

    $component = profile();

    expect(true)
        ->toBe($component->instance()->isLockForm());
});

test('reset input success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    profile()
        ->set('name', 'Arya')
        ->set('nim', 22040004)
        ->set('study_program_id', 1)
        ->set('password', 'rahasia')
        ->call('resetInput')
        ->assertNotSet('name', 'Arya')
        ->assertNotSet('nim', 22040004)
        ->assertNotSet('study_program_id', 1)
        ->assertNotSet('password', 'rahasia');
});

test('update admin success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('admin@gmail.com')->first();

    actingAs($user);

    profile()
        ->set('name', 'Admin Update')
        ->set('email', 'adminupdate@gmail.com')
        ->set('password', 'secret123')
        ->set(
            'avatar',
            UploadedFile::fake()->image('test.jpg')
        )
        ->call('update')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('users', [
        'name' => 'Admin Update',
        'email' => 'adminupdate@gmail.com',
    ]);

    $this->assertDatabaseMissing('users', [
        'name' => $user->name,
        'email' => $user->email,
    ]);

    $new_user = User::whereEmail('adminupdate@gmail.com')->first();

    expect($user->avatar)->not()->toBe($new_user->avatar);

    expect(true)
        ->toBe(Hash::check(
            'secret123',
            $new_user->password
        ));
});


test('update admin failed validation', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('admin@gmail.com')->first();

    actingAs($user);

    profile()
        ->set('name', 'Admin Update')
        ->set('email', 'adminupdate@gmail.com')
        ->set('password', 'secret')
        ->set(
            'avatar',
            UploadedFile::fake()->image('test.jpg')
        )
        ->call('update')
        ->assertHasErrors([
            'password' => 'min'
        ]);

    $this->assertDatabaseMissing('users', [
        'name' => 'Admin Update',
        'email' => 'adminupdate@gmail.com',
    ]);

    $this->assertDatabaseHas('users', [
        'name' => $user->name,
        'email' => $user->email,
    ]);

    $new_user = User::whereEmail('adminupdate@gmail.com')->first();

    expect($new_user)->toBeNull();
});


test('update contributor success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    profile()
        ->set('name', 'Contributor Update')
        ->set('email', 'contributorupdate@gmail.com')
        ->set('password', 'secret123')
        ->set('nim', 22040005)
        ->set('study_program_id', StudyProgram::first()->id)
        ->set(
            'avatar',
            UploadedFile::fake()->image('test.jpg')
        )
        ->call('update')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('users', [
        'name' => 'Contributor Update',
        'email' => 'contributorupdate@gmail.com',
    ]);

    $this->assertDatabaseMissing('users', [
        'name' => $user->name,
        'email' => $user->email,
    ]);

    $new_user = User::whereEmail('contributorupdate@gmail.com')->first();

    expect($user->avatar)->not()->toBe($new_user->avatar);

    expect(true)
        ->toBe(Hash::check(
            'secret123',
            $new_user->password
        ));
});

test('update contributor failed validation', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $user = User::whereEmail('contributor@gmail.com')->first();

    actingAs($user);

    profile()
        ->set('name', 'Contributor Update')
        ->set('email', 'contributorupdate@gmail.com')
        ->set('password', 'secret')
        ->set('nim', 22040005)
        ->set('study_program_id', StudyProgram::first()->id)
        ->set(
            'avatar',
            UploadedFile::fake()->image('test.jpg')
        )
        ->call('update')
        ->assertHasErrors([
            'password' => 'min'
        ]);

    $this->assertDatabaseMissing('users', [
        'name' => 'Contributor Update',
        'email' => 'contributorupdate@gmail.com',
    ]);

    $this->assertDatabaseHas('users', [
        'name' => $user->name,
        'email' => $user->email,
    ]);

    $new_user = User::whereEmail('contributorupdate@gmail.com')->first();

    expect($new_user)->toBeNull();
});
