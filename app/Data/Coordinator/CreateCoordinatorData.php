<?php

declare(strict_types=1);

namespace App\Data\Coordinator;

use Spatie\LaravelData\Data;

class CreateCoordinatorData extends Data
{
    public function __construct(
        public string $name,
        public int|string $nidn,
        public string $position,
        public int|string $study_program_id
    ) {}
}
