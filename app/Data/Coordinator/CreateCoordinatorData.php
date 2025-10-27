<?php

namespace App\Data\Coordinator;

use Spatie\LaravelData\Data;

class CreateCoordinatorData extends Data
{
    public function __construct(
        public string $name,
        public int $nidn,
        public string $position,
        public int $study_program_id
    ) {}
}
