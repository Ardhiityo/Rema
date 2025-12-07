<?php

use Livewire\Livewire;
use App\Livewire\Author\AuthorList;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;

test('get authors property success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $component = Livewire::test(AuthorList::class)
        ->set('status_filter', 'pending');

    expect($component->authors->total())->toBe(1);
    expect($component->authors->first()->nim)->toBe('22040004');
    expect($component->authors->first()->short_name)->toBe('John doe');
});

test('reset input success', function () {
    Livewire::test(AuthorList::class)
        ->set('keyword', 'Arya')
        ->set('status_filter', 'pending')
        ->set('study_program_slug', 'teknik-informatika')
        ->call('resetInput')
        ->assertNotSet('keyword', 'Arya')
        ->assertNotSet('status_filter', 'pending')
        ->assertNotSet('study_program_slug', 'teknik-informatika');
});
