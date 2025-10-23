<?php

namespace App\Repositories\Contratcs;

use App\Data\Note\NoteData;
use App\Data\Note\CreateNoteData;
use App\Data\Note\UpdateNoteData;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

interface NoteRepositoryInterface
{
    public function create(CreateNoteData $create_note_data): NoteData|Throwable;
    public function update(UpdateNoteData $update_note_data, int $note_id): NoteData|Throwable;
    public function delete(int $note_id): bool|Throwable;
    public function findById(int $note_id): NoteData|Throwable;
    public function findByMetaDataId(int $metadata_id): LengthAwarePaginator;
}
