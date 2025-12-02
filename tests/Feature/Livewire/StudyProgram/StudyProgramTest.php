<?php

use Livewire\Livewire;
use App\Livewire\StudyProgram;

test('render success', function () {
    Livewire::test(StudyProgram::class)
        ->assertSeeText('Study Programs')
        ->assertSeeText('All Study Programs Data Listed.');
});
