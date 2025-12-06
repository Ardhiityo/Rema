<?php

use Livewire\Livewire;
use App\Livewire\StudyProgramList;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

test('reset input success', function () {
    categoryForm()
        ->set('keyword', 'Cyber Security')
        ->call('resetInput')
        ->assertNotSet('keyword', 'Cyber Security')
        ->assertSet('keyword', '');
});

test('filter category by keyword success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $component = Livewire::test(StudyProgramList::class)
        ->set('keyword', 'Teknik Informatika')
        ->assertSet('keyword', 'Teknik Informatika');

    expect($component->study_programs)->toBeInstanceOf(LengthAwarePaginator::class);
    expect($component->study_programs->total())->toBe(1);
    expect($component->study_programs->first()->name)->toBe('Teknik Informatika');
});
