<?php

declare(strict_types=1);

namespace App\Data\Activity;

use Spatie\LaravelData\Data;

class CreateActivityData extends Data
{
    public function __construct(
        public string $ip,
        public string $user_agent,
        public int|null|string $user_id,
        public int|string $meta_data_id,
        public int|string $category_id
    ) {}
}
