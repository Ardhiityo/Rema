<?php

use Livewire\Livewire;
use App\Models\Coordinator;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Report\RepositoryReport;

test('mount success', function () {
    Storage::fake('public');

    Livewire::test(RepositoryReport::class)
        ->assertSet('year', now()->year);
});

test('reset input success', function () {
    Livewire::test(RepositoryReport::class)
        ->set('includes', ['skripsi', 'journal'])
        ->set('coordinator_id', 1)
        ->call('resetInput')
        ->assertSet('includes', [])
        ->assertSet('coordinator_id', '')
        ->assertSet('year', '');
});

test('download success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    $component = Livewire::test(RepositoryReport::class)
        ->set('year', 2025)
        ->set('includes', ['skripsi'])
        ->set('coordinator_id', $coordinator->id)
        ->instance();

    $response = $component->download();

    expect($response->headers->get('content-disposition'))
        ->toBe('attachment; filename="Repositories 2025.pdf"');
});

test('download failed validation', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    Livewire::test(RepositoryReport::class)
        ->set('year', 1995)
        ->set('includes', ['skripsi'])
        ->set('coordinator_id', $coordinator->id)
        ->call('download')
        ->assertHasErrors([
            'year' => 'exists'
        ]);
});

test('render success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    Livewire::test(RepositoryReport::class)
        ->assertSeeText($coordinator->name);
});
