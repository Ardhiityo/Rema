<?php

use Illuminate\Support\Str;
use App\Models\StudyProgram;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('form title success', function () {
    $component = studyProgramForm()
        ->set('is_update', true);

    expect($component->formTitle)->toBe('Edit Study Program');

    $component = studyProgramForm()
        ->set('is_update', false);

    expect($component->formTitle)->toBe('Create New Study Program');
});

test('create failed validation', function () {
    studyProgramForm()
        ->set('name', '')
        ->assertSet('name', '')
        ->call('create')
        ->assertHasErrors([
            'name' => 'required',
            'slug' => 'required'
        ]);

    $this->assertDatabaseCount('study_programs', 0);
});

test('create failed validation already exists', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    studyProgramForm()
        ->set('name', 'Teknik Informatika')
        ->assertSet('name', 'Teknik Informatika')
        ->call('create')
        ->assertHasErrors([
            'slug' => 'unique'
        ]);

    $this->assertDatabaseCount('study_programs', 2);

    $this->assertDatabaseHas('study_programs', [
        'name' => 'Teknik Informatika',
        'slug' => Str::slug('Teknik Informatika')
    ]);
});

test('edit success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $study_program = StudyProgram::first();

    studyProgramForm()
        ->call('edit', $study_program->id)
        ->assertSet('name', $study_program->name)
        ->assertSet('study_program_id', $study_program->id)
        ->assertSet('is_update', true);
});

test('edit failed not found', function () {
    studyProgramForm()->call('edit', 1)
        ->assertSet('is_update', false);
});

test('update success', function () {
    $this->seed(DatabaseSeeder::class);

    $study_program = StudyProgram::first();

    studyProgramForm()
        ->set('study_program_id', $study_program->id)
        ->set('name', 'Cyber Security')
        ->call('update')
        ->assertDispatched('refresh-study-programs');

    $this->assertDatabaseCount('study_programs', 2);

    $this->assertDatabaseHas('study_programs', [
        'name' => 'Cyber Security',
        'slug' => Str::slug('Cyber Security')
    ]);
});

test('update failed not found', function () {
    studyProgramForm()
        ->set('study_program_id', 1)
        ->set('name', 'Cyber Security')
        ->call('update')
        ->assertNotDispatched('refresh-study-programs');

    $this->assertDatabaseCount('study_programs', 0);

    $this->assertDatabaseMissing('study_programs', [
        'name' => 'Cyber Security',
        'slug' => Str::slug('Cyber Security')
    ]);
});

test('delete confirm success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $study_program = StudyProgram::first();

    studyProgramForm()->call('deleteConfirm', $study_program->id)
        ->assertSet('study_program_id', $study_program->id);
});

test('delete confirm not found', function () {
    studyProgramForm()->call('deleteConfirm', 1)
        ->assertNotSet('study_program_id', 1);
});

test('delete success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $study_program = StudyProgram::first();

    studyProgramForm()
        ->set('study_program_id', $study_program->id)
        ->call('delete')
        ->assertSet('is_update', false)
        ->assertDispatched('refresh-study-programs');

    $this->assertDatabaseCount('study_programs', 1);
});

test('delete failed not found', function () {
    studyProgramForm()
        ->set('study_program_id', 1)
        ->call('delete')
        ->assertSet('is_update', false)
        ->assertNotDispatched('refresh-study-programs');

    $this->assertDatabaseCount('study_programs', 0);
});

test('reset input success', function () {
    studyProgramForm()
        ->set('name', 'Cyber Security')
        ->set('slug', 'Cyber Security')
        ->set('is_update', true)
        ->call('resetInput')
        ->assertNotSet('name', 'Cyber Security')
        ->assertNotSet('slug', 'Cyber Security')
        ->assertNotSet('is_update', true)
        ->assertSet('name', '')
        ->assertSet('slug', '')
        ->assertSet('is_update', false);
});
