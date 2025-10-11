<?php

namespace App\Data\Category;

use Spatie\LaravelData\Data;

class CreateCategoryData extends Data
{
    public function __construct(
        public string $name,
        public string $slug
    ) {}
}
