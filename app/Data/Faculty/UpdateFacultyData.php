<?php

declare(strict_types=1);

namespace App\Data\Faculty;

use Spatie\LaravelData\Data;

class UpdateFacultyData extends Data
{
    public function __construct(
        public string $name,
        public string $slug
    ) {}
}
