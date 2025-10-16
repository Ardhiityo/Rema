<?php

namespace App\Repositories\Eloquent;

use App\Models\Note;
use App\Data\Note\NoteData;
use App\Data\Note\CreateNoteData;
use App\Data\Note\UpdateNoteData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contratcs\NoteRepositoryInterface;

class NoteRepository implements NoteRepositoryInterface
{
    public function create(CreateNoteData $create_note_data): NoteData
    {
        $note = Note::create([
            'meta_data_id' => $create_note_data->meta_data_id,
            'message' => $create_note_data->message
        ]);

        return NoteData::fromModel($note);
    }

    public function update(UpdateNoteData $update_note_data, int $note_id): NoteData|null
    {
        try {
            $note = Note::findOrFail($note_id);

            $note->update([
                'message' => $update_note_data->message
            ]);

            return NoteData::fromModel($note->refresh());
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function delete(int $note_id): bool
    {
        try {
            $note = Note::findOrFail($note_id);
            return $note->delete();
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function findById(int $note_id): NoteData|null
    {
        try {
            $note = Note::findOrFail($note_id);

            return NoteData::fromModel($note);
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function findByMetaDataId(int $metadata_id): LengthAwarePaginator
    {
        $notes = Note::where('meta_data_id', $metadata_id)
            ->orderByDesc('id')
            ->paginate(10);

        return NoteData::collect($notes);
    }
}
