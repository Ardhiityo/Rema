<?php

use Livewire\Livewire;
use App\Livewire\CategoryList;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Pagination\LengthAwarePaginator;

test('reset input success', function () {
    categoryForm()
        ->set('keyword', 'Cyber Security')
        ->call('resetInput')
        ->assertNotSet('keyword', 'Cyber Security')
        ->assertSet('keyword', '');
});

test('filter category by keyword success', function () {
    $this->seed(DatabaseSeeder::class);

    $component = Livewire::test(CategoryList::class)
        ->set('keyword', 'Skripsi');

    expect($component->categories)->toBeInstanceOf(LengthAwarePaginator::class);
    expect($component->categories->total())->toBe(1);
    expect($component->categories->first()->name)->toBe('Skripsi');
});
