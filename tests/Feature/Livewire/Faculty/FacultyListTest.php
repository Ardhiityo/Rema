<?php

use App\Models\Faculty;
use Database\Seeders\FacultySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('faculty list', function () {
    facultyList()
        ->assertStatus(200)
        ->assertViewIs('livewire.faculty.list');
});

test('it lists faculties', function () {
    $this->seed(FacultySeeder::class);

    facultyList()
        ->assertSee(Faculty::first()->name);
});

test('it can search faculties by keyword', function () {
    $this->seed(FacultySeeder::class);

    $faculty = Faculty::first();

    facultyList()
        ->set('keyword', $faculty->name)
        ->assertSee($faculty->name);
});

test('it can reset input', function () {
    facultyList()
        ->set('keyword', 'some keyword')
        ->call('resetInput')
        ->assertSet('keyword', '');
});

test('it responds to refresh faculties event', function () {
    facultyList()
        ->dispatch('refresh-faculties')
        ->assertStatus(200);
});
