<?php

namespace App\Data\Note;

use Spatie\LaravelData\Data;

class CreateNoteData extends Data
{
    public function __construct(
        public int $meta_data_id,
        public string $message
    ) {}
}
