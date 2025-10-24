<?php

namespace App\Data\Activity;

use App\Models\Activity;
use Spatie\LaravelData\Data;

class ActivityReportData extends Data
{
    public function __construct(
        public string $category,
        public string|int $total
    ) {}

    public static function fromModel(Activity $activity): self
    {
        return new self(
            $activity->category->name,
            $activity->total
        );
    }
}
