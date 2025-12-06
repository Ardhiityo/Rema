<?php

use Livewire\Livewire;
use App\Livewire\Report;

test('example', function () {
    Livewire::test(Report::class)
        ->assertSeeText('Reports')
        ->assertSeeText('Generate All Reports.');
});
