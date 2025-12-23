<?php

declare(strict_types=1);

namespace App\Data\Note;

use App\Models\Note;
use Spatie\LaravelData\Data;

class NoteData extends Data
{
    public function __construct(
        public int $id,
        public string $message,
        public string $created_at
    ) {}

    public static function fromModel(Note $note)
    {
        return new self(
            $note->id,
            $note->message,
            $note->created_at->format('d F Y H:i')
        );
    }
}
