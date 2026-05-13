<?php

declare(strict_types=1);

namespace App\Data\Faculty;

use App\Models\Faculty;
use Spatie\LaravelData\Data;

class FacultyData extends Data
{
    public function __construct(
        public int|string $id,
        public string $name,
        public string $slug
    ) {}

    public static function fromModel(Faculty $faculty)
    {
        return new self(
            $faculty->id,
            $faculty->name,
            $faculty->slug
        );
    }
}
