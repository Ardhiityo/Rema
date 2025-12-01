<?php

use Livewire\Livewire;
use App\Livewire\StudyProgram;

test('render study program success', function () {
    Livewire::test(StudyProgram::class)
        ->assertSeeText('Study Programs')
        ->assertSeeText('All study programs data listed.');
});
