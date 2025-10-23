<?php

namespace App\Data\Activity;

use App\Models\Activity;
use Spatie\LaravelData\Data;

class ActivityData extends Data
{
    public function __construct(
        public string $ip,
        public string $user_agent,
        public int|null $user_id,
        public int $meta_data_id,
        public int $category_id
    ) {}

    public static function fromModel(Activity $activity): self
    {
        return new self(
            $activity->ip,
            $activity->user_agent,
            $activity->user_id,
            $activity->meta_data_id,
            $activity->category_id
        );
    }
}
