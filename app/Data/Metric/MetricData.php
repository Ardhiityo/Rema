<?php

namespace App\Data\Metric;

use Spatie\LaravelData\Data;

class MetricData extends Data
{
    public function __construct(
        public string $study_program,
        public int $total
    ) {}
}
