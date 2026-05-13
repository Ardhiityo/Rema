<?php

use App\Models\Faculty;
use Database\Seeders\FacultySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('faculty form page', function () {
    facultyForm()
        ->assertStatus(200)
        ->assertViewIs('livewire.faculty.form');
});

test('it can create a faculty', function () {
    facultyForm()
        ->set('name', 'New Faculty')
        ->call('create')
        ->assertHasNoErrors()
        ->assertDispatched('refresh-faculties')
        ->assertSee('The faculty was successfully created.');

    expect(Faculty::where('name', 'New Faculty')->exists())->toBeTrue();
});

test('it validates required fields', function () {
    facultyForm()
        ->set('name', '')
        ->call('create')
        ->assertHasErrors(['slug' => 'required']);
});

test('it validates unique slug', function () {
    $this->seed(FacultySeeder::class);

    $faculty = Faculty::first();

    facultyForm()
        ->set('name', $faculty->name)
        ->call('create')
        ->assertHasErrors(['slug' => 'unique']);
});

test('it can load faculty data for editing', function () {
    $this->seed(FacultySeeder::class);

    $faculty = Faculty::first();

    facultyForm()
        ->dispatch('faculty-edit', faculty_id: $faculty->id)
        ->assertSet('faculty_id', $faculty->id)
        ->assertSet('name', $faculty->name)
        ->assertSet('is_update', true)
        ->assertSet('formTitle', 'Edit Faculty');
});

test('it can update a faculty', function () {
    $this->seed(FacultySeeder::class);

    $faculty = Faculty::first();

    facultyForm()
        ->set('faculty_id', $faculty->id)
        ->set('is_update', true)
        ->set('name', 'Updated Name')
        ->call('update')
        ->assertHasNoErrors()
        ->assertDispatched('refresh-faculties')
        ->assertSee('The faculty was successfully updated.');

    expect(Faculty::find($faculty->id)->name)->toBe('Updated Name');
});

test('it can confirm deletion', function () {
    $this->seed(FacultySeeder::class);

    $faculty = Faculty::first();

    facultyForm()
        ->dispatch('faculty-delete-confirm', faculty_id: $faculty->id)
        ->assertSet('faculty_id', $faculty->id);
});

test('it can delete a faculty', function () {
    $this->seed(FacultySeeder::class);

    $faculty = Faculty::first();

    facultyForm()
        ->set('faculty_id', $faculty->id)
        ->dispatch('faculty-delete')
        ->assertDispatched('refresh-faculties')
        ->assertSee('The faculty was successfully deleted.');

    expect(Faculty::find($faculty->id))->toBeNull();
});

test('it can reset input', function () {
    facultyForm()
        ->set('name', 'Some Name')
        ->set('slug', 'some-slug')
        ->set('is_update', true)
        ->call('resetInput')
        ->assertSet('name', '')
        ->assertSet('slug', '')
        ->assertSet('is_update', false);
});
