<?php

namespace App\Data\Author;

use Spatie\LaravelData\Data;

class UpdateAuthorData extends Data
{
    public function __construct(
        public int $nim,
        public int $study_program_id,
        public string $status
    ) {}
}
