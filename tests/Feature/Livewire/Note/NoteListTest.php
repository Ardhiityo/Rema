<?php

use App\Models\Note;
use Livewire\Livewire;
use App\Models\MetaData;
use App\Livewire\Note\NoteList;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
