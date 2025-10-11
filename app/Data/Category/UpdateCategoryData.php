<?php

namespace App\Data\Category;

use Spatie\LaravelData\Data;

class UpdateCategoryData extends Data
{
    public function __construct(
        public string $name,
        public string $slug
    ) {}
}
