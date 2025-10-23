<?php

namespace App\Data\Activity;

use Spatie\LaravelData\Data;

class CreateActivityData extends Data
{
    public function __construct(
        public string $ip,
        public string $user_agent,
        public int|null $user_id,
        public int $meta_data_id,
        public int $category_id
    ) {}
}
