<?php

namespace App\Data\Activity;

use Spatie\LaravelData\Data;

class ActivityCategoryData extends Data
{
    public function __construct(
        public string $category,
        public int $total
    ) {}

    public static function fromModel($item): self
    {
        return new self(
            $item['category'],
            $item['total']
        );
    }
}
