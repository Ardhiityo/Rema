<?php

declare(strict_types=1);

namespace App\Data\Activity;

use Spatie\LaravelData\Data;

class ActivityCategoryData extends Data
{
    public function __construct(
        public string $category,
        public int|string $total
    ) {}

    public static function fromModel($item): self
    {
        return new self(
            $item['category'],
            $item['total']
        );
    }
}
