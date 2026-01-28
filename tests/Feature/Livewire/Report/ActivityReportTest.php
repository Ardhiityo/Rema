<?php

use Livewire\Livewire;
use Database\Seeders\DatabaseSeeder;
use App\Livewire\Report\ActivityReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('download success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    Livewire::test(ActivityReport::class)
        ->set('year', 2025)
        ->call('download')
        ->assertSet('year', '')
        ->assertRedirectToRoute(
            'reports.activities.download',
            ['year' => 2025]
        );
});

test('download failed validation', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    Livewire::test(ActivityReport::class)
        ->set('year', 1995)
        ->call('download')
        ->assertHasErrors([
            'year' => 'exists'
        ]);
});

test('reset input success', function () {
    Livewire::test(ActivityReport::class)
        ->set('year', 2025)
        ->assertSet('year', 2025)
        ->call('resetInput')
        ->assertSet('year', '');
});
