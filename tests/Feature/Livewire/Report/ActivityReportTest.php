<?php

use Livewire\Livewire;
use App\Livewire\ActivityReport;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('download success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $component = Livewire::test(ActivityReport::class)
        ->set('year', 2025)
        ->instance();

    $response = $component->download();

    expect($response->headers->get('content-disposition'))
        ->toBe('attachment; filename="Activities 2025.pdf"');
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
