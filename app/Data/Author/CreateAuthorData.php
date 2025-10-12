<?php

namespace App\Data\Author;

use Spatie\LaravelData\Data;

class CreateAuthorData extends Data
{
    public function __construct(
        public int $user_id,
        public int $nim,
        public int $study_program_id,
        public string $status
    ) {}
}
