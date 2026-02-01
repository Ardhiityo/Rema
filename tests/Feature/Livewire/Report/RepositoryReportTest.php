<?php

use Livewire\Livewire;
use App\Models\Coordinator;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Report\AuthorReport;

test('mount success', function () {
    Storage::fake('public');

    Livewire::test(AuthorReport::class)
        ->assertSet('year', now()->year);
});

test('reset input success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    Livewire::test(AuthorReport::class)
        ->set('includes', ['skripsi', 'journal'])
        ->set('nidn', $coordinator->nidn)
        ->call('resetInput')
        ->assertSet('includes', [])
        ->assertSet('nidn', '')
        ->assertSet('year', '');
});

test('download success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    Livewire::test(AuthorReport::class)
        ->set('year', 2025)
        ->set('includes', ['skripsi'])
        ->set('nidn', $coordinator->nidn)
        ->call('download')
        ->assertSet('year', '')
        ->assertSet('includes', [])
        ->assertSet('nidn', '')
        ->assertRedirectToRoute(
            'reports.repositories.download',
            [
                'nidn' => $coordinator->nidn,
                'year' => 2025,
                'includes' => json_encode(['skripsi'])
            ]
        );
});

test('download failed validation', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    Livewire::test(AuthorReport::class)
        ->set('year', 1995)
        ->set('includes', ['skripsi'])
        ->set('nidn', $coordinator->nidn)
        ->call('download')
        ->assertHasErrors([
            'year' => 'exists'
        ]);
});

test('render success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    Livewire::test(AuthorReport::class)
        ->assertSeeText($coordinator->name);
});
