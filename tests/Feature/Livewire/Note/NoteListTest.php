<?php

use App\Livewire\NoteList;
use App\Models\MetaData;
use App\Models\Note;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('mount success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();

    Livewire::test(
        NoteList::class,
        ['meta_data_id' => $meta_data->id]
    )
        ->assertSet('meta_data_id', $meta_data->id);
});

test('render success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();
    $note = Note::first();

    $component = Livewire::test(
        NoteList::class,
        ['meta_data_id' => $meta_data->id]
    )->assertSeeText($note->message);
});
