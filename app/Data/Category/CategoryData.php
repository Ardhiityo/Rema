<?php

namespace App\Data\Category;

use App\Models\Category;
use Spatie\LaravelData\Data;

class CategoryData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string $created_at
    ) {}

    public static function fromModel(Category $category)
    {
        return new self(
            $category->id,
            $category->name,
            $category->slug,
            $category->created_at->format('d F Y')
        );
    }
}
