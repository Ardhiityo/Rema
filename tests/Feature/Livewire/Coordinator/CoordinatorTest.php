<?php

use Livewire\Livewire;
use App\Livewire\Coordinator\Coordinator;

test('render success', function () {
    Livewire::test(Coordinator::class)
        ->assertSeeText('Coordinators')
        ->assertSeeText('All Coordinators ');
});
