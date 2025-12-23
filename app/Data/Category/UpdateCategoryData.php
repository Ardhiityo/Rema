<?php

declare(strict_types=1);

namespace App\Data\Category;

use Spatie\LaravelData\Data;

class UpdateCategoryData extends Data
{
    public function __construct(
        public string $name,
        public string $slug
    ) {}
}
