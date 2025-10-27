<?php

namespace App\Data\Coordinator;

use App\Models\Coordinator;
use Spatie\LaravelData\Data;

class CoordinatorData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public int $nidn,
        public string $position
    ) {}

    public static function fromModel(Coordinator $coordinator): self
    {
        return new self(
            $coordinator->id,
            $coordinator->name,
            $coordinator->nidn,
            $coordinator->position
        );
    }
}
