<?php

declare(strict_types=1);

namespace App\Data\Author;

use Spatie\LaravelData\Data;

class UpdateAuthorData extends Data
{
    public function __construct(
        public int|string $nim,
        public int|string $study_program_id,
        public string $status
    ) {}
}
