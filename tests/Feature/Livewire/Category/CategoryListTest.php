<?php

use Livewire\Livewire;
use Database\Seeders\DatabaseSeeder;
use App\Livewire\Category\CategoryList;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

test('reset input success', function () {
    categoryForm()
        ->set('keyword', 'Skripsi')
        ->call('resetInput')
        ->assertNotSet('keyword', 'Skripsi')
        ->assertSet('keyword', '');
});

test('filter category by keyword success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $component = Livewire::test(CategoryList::class)
        ->set('keyword', 'Skripsi');

    expect($component->categories)->toBeInstanceOf(LengthAwarePaginator::class);
    expect($component->categories->total())->toBe(1);
    expect($component->categories->first()->name)->toBe('Skripsi');
});
