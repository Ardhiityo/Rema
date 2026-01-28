<?php

use Livewire\Livewire;
use App\Models\MetaData;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Repository\RepositoryDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('render success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();

    $meta_data->load(
        'author.user',
        'categories'
    );

    Livewire::test(
        RepositoryDetail::class,
        ['meta_data' => $meta_data]
    )
        ->assertSeeText($meta_data->author_study_program)
        ->assertSeeText($meta_data->author_name)
        ->assertSeeText($meta_data->author_nim)
        ->assertSeeText($meta_data->year);
});
