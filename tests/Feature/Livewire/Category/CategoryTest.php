<?php

use Livewire\Livewire;
use App\Livewire\Category;

test('render category success', function () {
    Livewire::test(Category::class)
        ->assertSeeText('Categories')
        ->assertSeeText('All categories data listed.');
});
