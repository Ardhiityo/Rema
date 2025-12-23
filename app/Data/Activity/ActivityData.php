<?php

declare(strict_types=1);

namespace App\Data\Activity;

use App\Models\Activity;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Computed;

class ActivityData extends Data
{
    #[Computed()]
    public string $short_meta_data;

    public function __construct(
        public string $meta_data,
        public string $meta_data_slug,
        public string $category,
        public string $category_slug,
        public int $views,
    ) {
        $this->short_meta_data = Str::limit($meta_data, 55, '...');
    }

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
