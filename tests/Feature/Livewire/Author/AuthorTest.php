<?php

use Livewire\Livewire;
use App\Livewire\Author\Author;

test('render success', function () {
    Livewire::test(Author::class)
        ->assertSeeText('Authors')
        ->assertSeeText('All Authors Data Listed.');
});
