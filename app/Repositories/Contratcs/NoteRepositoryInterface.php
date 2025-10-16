<?php

namespace App\Repositories\Contratcs;

use App\Data\Note\NoteData;
use App\Data\Note\CreateNoteData;
use App\Data\Note\UpdateNoteData;
use Illuminate\Pagination\LengthAwarePaginator;

interface NoteRepositoryInterface
{
    public function create(CreateNoteData $create_note_data): NoteData;
    public function update(UpdateNoteData $update_note_data, int $note_id): NoteData|null;
    public function delete(int $note_id): bool;
    public function findById(int $note_id): NoteData|null;
    public function findByMetaDataId(int $metadata_id): LengthAwarePaginator;
}
