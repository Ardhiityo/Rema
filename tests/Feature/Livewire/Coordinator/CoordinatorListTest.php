<?php

use Livewire\Livewire;
use App\Models\Coordinator;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Coordinator\CoordinatorList;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('reset input success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    Livewire::test(CoordinatorList::class)
        ->set('keyword', 'tes 123')
        ->call('resetInput')
        ->assertSet('keyword', '');
});

test('render success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $coordinator = Coordinator::first();

    Livewire::test(CoordinatorList::class)
        ->set('keyword', 22040004)
        ->assertSeeText($coordinator->name)
        ->assertSeeText($coordinator->nidn);
});
