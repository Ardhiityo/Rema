<?php

use Livewire\Livewire;
use App\Livewire\Category;

test('render success', function () {
    Livewire::test(Category::class)
        ->assertSeeText('Categories')
        ->assertSeeText('All Categories Data Listed.');
});
