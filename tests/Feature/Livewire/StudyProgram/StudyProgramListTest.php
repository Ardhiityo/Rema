<?php

use App\Models\StudyProgram;
use Database\Seeders\FacultySeeder;
use Database\Seeders\StudyProgramSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('study program list page', function () {
    studyProgramList()
        ->assertStatus(200)
        ->assertViewIs('livewire.study-program.list');
});

test('it can search study programs by keyword', function () {
    $this->seed(FacultySeeder::class);
    $this->seed(StudyProgramSeeder::class);

    $study_program = StudyProgram::first();

    studyProgramList()
        ->set('keyword', $study_program->name)
        ->assertSee($study_program->name);
});

test('it can reset input', function () {
    studyProgramList()
        ->set('keyword', 'some keyword')
        ->call('resetInput')
        ->assertSet('keyword', '');
});

test('it responds to refresh study programs event', function () {
    studyProgramList()
        ->dispatch('refresh-study-programs')
        ->assertStatus(200);
});