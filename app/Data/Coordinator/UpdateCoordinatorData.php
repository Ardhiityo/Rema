<?php

namespace App\Data\Coordinator;

use Spatie\LaravelData\Data;

class UpdateCoordinatorData extends Data
{
    public function __construct(
        public string $name,
        public int $nidn,
        public string $position
    ) {}
}
