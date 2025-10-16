<?php

namespace App\Data\Note;

use Spatie\LaravelData\Data;

class UpdateNoteData extends Data
{
    public function __construct(
        public string $message
    ) {}
}
