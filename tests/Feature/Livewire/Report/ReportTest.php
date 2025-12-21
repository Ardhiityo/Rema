<?php

use Livewire\Livewire;
use App\Livewire\Report\Report;

test('render success', function () {
    Livewire::test(Report::class)
        ->assertSeeText('Reports')
        ->assertSeeText('Generate All Reports.');
});
