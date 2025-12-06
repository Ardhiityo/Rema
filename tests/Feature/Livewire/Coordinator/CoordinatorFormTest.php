<?php

use App\Models\Coordinator;
use App\Models\StudyProgram;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('form title create success', function () {
    $component = coordinatorForm();

    expect('Create New Coordinator')
        ->toBe($component->instance()->formTitle());
});

test('form title edit success', function () {
    $component = coordinatorForm()
        ->set('is_update', true);

    expect('Edit Coordinator')
        ->toBe($component->instance()->formTitle());
});

test('create success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $study_program = StudyProgram::whereSlug('manajemen-informatika')->first();

    expect($study_program->id)->not()->toBeNull();

    coordinatorForm()
        ->set('nidn', 12345)
        ->set('study_program_id', $study_program->id)
        ->set('name', 'Budi Nugraha')
        ->set('position', 'Dekan Fakultas Ilmu Komputer')
        ->call('create')
        ->assertHasNoErrors()
        ->assertDispatched('refresh-coordinators')
        ->assertNotSet('nidn', 12345)
        ->assertNotSet('study_program_id', $study_program->id)
        ->assertNotSet('name', 'Budi Nugraha')
        ->assertNotSet('position', 'Dekan Fakultas Ilmu Komputer')
        ->assertSet('is_update', false);

    $this->assertDatabaseCount('coordinators', 2);

    $this->assertDatabaseHas('coordinators', [
        'name' => 'Budi Nugraha',
        'nidn' => 12345,
        'position' => 'Dekan Fakultas Ilmu Komputer',
        'study_program_id' => $study_program->id
    ]);
});

test('create failed validation already exists', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $study_program = StudyProgram::whereSlug('teknik-informatika')->first();

    expect($study_program->id)->not()->toBeNull();

    coordinatorForm()
        ->set('nidn', 12345)
        ->set('study_program_id', $study_program->id)
        ->set('name', 'Budi Nugraha')
        ->set('position', 'Dekan Fakultas Ilmu Komputer')
        ->call('create')
        ->assertHasErrors([
            'study_program_id' => 'unique'
        ])
        ->assertNotDispatched('refresh-coordinators')
        ->assertSet('nidn', 12345)
        ->assertSet('study_program_id', $study_program->id)
        ->assertSet('name', 'Budi Nugraha')
        ->assertSet('position', 'Dekan Fakultas Ilmu Komputer')
        ->assertSet('is_update', false);

    $this->assertDatabaseCount('coordinators', 1);

    $this->assertDatabaseMissing('coordinators', [
        'name' => 'Budi Nugraha',
        'nidn' => 12345,
        'position' => 'Dekan Fakultas Ilmu Komputer',
        'study_program_id' => $study_program->id
    ]);
});

test('edit success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    coordinatorForm()
        ->call('edit', $coordinator->id)
        ->assertSet('coordinator_id', $coordinator->id)
        ->assertSet('nidn', $coordinator->nidn)
        ->assertSet('name', $coordinator->name)
        ->assertSet('position', $coordinator->position)
        ->assertSet('study_program_id', $coordinator->study_program_id)
        ->assertSet('is_update', true);
});

test('edit failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    coordinatorForm()
        ->call('edit', 100)
        ->assertNotSet('coordinator_id', $coordinator->id)
        ->assertNotSet('nidn', $coordinator->nidn)
        ->assertNotSet('name', $coordinator->name)
        ->assertNotSet('position', $coordinator->position)
        ->assertNotSet('study_program_id', $coordinator->study_program_id)
        ->assertNotSet('is_update', true);
});

test('update success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    $study_program = StudyProgram::whereSlug('manajemen-informatika')
        ->first();

    coordinatorForm()
        ->set('coordinator_id', $coordinator->id)
        ->set('study_program_id', $study_program->id)
        ->set('nidn', 22040005)
        ->set('name', 'Joko Nugraha')
        ->set('position', 'Kaprodi Fakultas Ilmu Komputer')
        ->set('is_update', true)
        ->call('update')
        ->assertDispatched('refresh-coordinators')
        ->assertNotSet('study_program_id', $study_program->id)
        ->assertNotSet('nidn', 22040005)
        ->assertNotSet('name', 'Joko Nugraha')
        ->assertNotSet('position', 'Kaprodi Fakultas Ilmu Komputer')
        ->assertNotSet('is_update', true);

    $this->assertDatabaseCount('coordinators', 1);

    $this->assertDatabaseHas('coordinators', [
        'name' => 'Joko Nugraha',
        'nidn' => 22040005,
        'position' => 'Kaprodi Fakultas Ilmu Komputer',
        'study_program_id' => $study_program->id
    ]);
});

test('update failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    $study_program = StudyProgram::whereSlug('manajemen-informatika')
        ->first();

    coordinatorForm()
        ->set('coordinator_id', 100)
        ->set('study_program_id', $study_program->id)
        ->set('nidn', 22040005)
        ->set('name', 'Joko Nugraha')
        ->set('position', 'Kaprodi Fakultas Ilmu Komputer')
        ->set('is_update', true)
        ->call('update')
        ->assertNotDispatched('refresh-coordinators')
        ->assertSet('study_program_id', $study_program->id)
        ->assertSet('nidn', 22040005)
        ->assertSet('name', 'Joko Nugraha')
        ->assertSet('position', 'Kaprodi Fakultas Ilmu Komputer')
        ->assertSet('is_update', true);

    $this->assertDatabaseCount('coordinators', 1);

    $this->assertDatabaseMissing('coordinators', [
        'name' => 'Joko Nugraha',
        'nidn' => 22040005,
        'position' => 'Kaprodi Fakultas Ilmu Komputer',
        'study_program_id' => $study_program->id
    ]);
});

test('delete confirm success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    coordinatorForm()
        ->call('deleteConfirm', $coordinator->id)
        ->assertSet('coordinator_id', $coordinator->id);
});

test('delete confirm not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    coordinatorForm()
        ->call('deleteConfirm', 100)
        ->assertNotSet('coordinator_id', $coordinator->id);
});

test('delete success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    coordinatorForm()
        ->set('coordinator_id', $coordinator->id)
        ->assertSet('coordinator_id', $coordinator->id)
        ->call('delete')
        ->assertDispatched('refresh-coordinators');

    $this->assertDatabaseCount('coordinators', 0);

    $this->assertDatabaseMissing('coordinators', [
        'name' => $coordinator->name,
        'nidn' => $coordinator->nidn,
        'position' => $coordinator->position,
        'study_program_id' => $coordinator->study_program_id
    ]);
});

test('delete failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    coordinatorForm()
        ->set('coordinator_id', 100)
        ->assertSet('coordinator_id', 100)
        ->call('delete')
        ->assertNotDispatched('refresh-coordinators');

    $this->assertDatabaseCount('coordinators', 1);

    $this->assertDatabaseHas('coordinators', [
        'name' => $coordinator->name,
        'nidn' => $coordinator->nidn,
        'position' => $coordinator->position,
        'study_program_id' => $coordinator->study_program_id
    ]);
});

test('reset input success', function () {
    coordinatorForm()
        ->set('is_update', true)
        ->set('name', 'tes 123')
        ->set('position', 'tes 123')
        ->set('nidn', 123)
        ->set('study_program_id', 100)
        ->call('resetInput')
        ->assertSet('name', '')
        ->assertSet('position', '')
        ->assertSet('nidn', '')
        ->assertSet('study_program_id', '')
        ->assertSet('is_update', false);
});
