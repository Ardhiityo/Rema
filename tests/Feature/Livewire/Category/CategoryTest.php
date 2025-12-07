<?php

use Livewire\Livewire;
use App\Livewire\Category\Category;

test('render success', function () {
    Livewire::test(Category::class)
        ->assertSeeText('Categories')
        ->assertSeeText('All Categories Data Listed.');
});
