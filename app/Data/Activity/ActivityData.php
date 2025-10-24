<?php

namespace App\Data\Activity;

use App\Models\Activity;
use Spatie\LaravelData\Data;

class ActivityData extends Data
{
    public function __construct(
        public string $meta_data,
        public string $meta_data_slug,
        public string $category,
        public string $category_slug,
        public int $views,
    ) {}

    public static function fromModel(Activity $activity): self
    {
        return new self(
            $activity->metadata->title,
            $activity->metadata->slug,
            $activity->category->name,
            $activity->category->slug,
            $activity->total
        );
    }
}
