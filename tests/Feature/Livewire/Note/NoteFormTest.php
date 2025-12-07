<?php

use App\Models\MetaData;
use App\Models\Note;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('mount success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();

    noteForm(['meta_data_id' => $meta_data->id])
        ->assertSet('meta_data_id', $meta_data->id);
});

test('form title success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();

    $component = noteForm(
        ['meta_data_id' => $meta_data->id]
    )
        ->set('is_update', true)
        ->assertSet('is_update', true);

    expect('Edit Note')
        ->toBe($component->instance()->formTitle());

    $component = noteForm(
        ['meta_data_id' => $meta_data->id]
    );

    expect('Create New Note')
        ->toBe($component->instance()->formTitle());
});

test('reset input success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();

    noteForm(['meta_data_id' => $meta_data->id])
        ->set('message', 'tes 123')
        ->call('resetInput')
        ->assertSet('message', '');
});

test('create success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();

    noteForm(['meta_data_id' => $meta_data->id])
        ->set('message', 'hello world')
        ->assertSet('message', 'hello world')
        ->call('create')
        ->assertHasNoErrors()
        ->assertDispatched('refresh-notes');

    $this->assertDatabaseCount('notes', 2);

    $this->assertDatabaseHas('notes', [
        'meta_data_id' => $meta_data->id,
        'message' => 'hello world'
    ]);
});

test('create failed validation', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();

    noteForm(['meta_data_id' => $meta_data->id])
        ->set('message', '')
        ->assertSet('message', '')
        ->call('create')
        ->assertHasErrors([
            'message' => 'required'
        ])
        ->assertNotDispatched('refresh-notes');

    $this->assertDatabaseCount('notes', 1);

    $this->assertDatabaseMissing('notes', [
        'meta_data_id' => $meta_data->id,
        'message' => ''
    ]);
});

test('edit success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();
    $note = Note::first();

    noteForm(['meta_data_id' => $meta_data->id])
        ->call('edit', $note->id)
        ->assertSet('note_id', $note->id)
        ->assertSet('message', $note->message)
        ->assertSet('is_update', true);
});

test('edit failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();
    $note = Note::first();

    noteForm(['meta_data_id' => $meta_data->id])
        ->call('edit', 100)
        ->assertNotSet('note_id', $note->id)
        ->assertNotSet('message', $note->message)
        ->assertNotSet('is_update', true);
});

test('update success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();
    $note = Note::first();

    noteForm(['meta_data_id' => $meta_data->id])
        ->set('note_id', $note->id)
        ->set('message', 'Tes 123')
        ->call('update')
        ->assertHasNoErrors()
        ->assertSet('is_update', false)
        ->assertDispatched('refresh-notes');

    $this->assertDatabaseCount('notes', 1);

    $this->assertDatabaseHas('notes', [
        'message' => 'Tes 123',
        'meta_data_id' => $meta_data->id
    ]);

    $this->assertDatabaseMissing('notes', [
        'message' => $note->message,
    ]);
});

test('update failed validation', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();
    $note = Note::first();

    noteForm(['meta_data_id' => $meta_data->id])
        ->set('note_id', $note->id)
        ->set('message', '')
        ->assertSet('message', '')
        ->call('update')
        ->assertHasErrors([
            'message' => 'required'
        ])
        ->assertSet('is_update', false)
        ->assertNotDispatched('refresh-notes');

    $this->assertDatabaseCount('notes', 1);

    $this->assertDatabaseMissing('notes', [
        'message' => ''
    ]);

    $this->assertDatabaseHas('notes', [
        'message' => $note->message,
    ]);
});

test('delete confirm success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();
    $note = Note::first();

    noteForm(['meta_data_id' => $meta_data->id])
        ->call('deleteConfirm', $note->id)
        ->assertSet('note_id', $note->id);
});

test('delete success', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();
    $note = Note::first();

    noteForm(['meta_data_id' => $meta_data->id])
        ->set('note_id', $note->id)
        ->assertSet('note_id', $note->id)
        ->call('delete')
        ->assertDispatched('refresh-notes');

    $this->assertDatabaseCount('notes', 0);

    $this->assertDatabaseMissing('notes', [
        'meta_data_id' => $meta_data->id,
        'message' => $note->message
    ]);
});

test('delete failed not found', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);

    $meta_data = MetaData::first();
    $note = Note::first();

    noteForm(['meta_data_id' => $meta_data->id])
        ->set('note_id', 100)
        ->assertSet('note_id', 100)
        ->call('delete')
        ->assertNotDispatched('refresh-notes');

    $this->assertDatabaseCount('notes', 1);

    $this->assertDatabaseHas('notes', [
        'meta_data_id' => $meta_data->id,
        'message' => $note->message
    ]);
});
