<?php

namespace App\Data\Metric;

use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Livewire\Attributes\Computed;

class MetricData extends Data
{
    #[Computed()]
    public string $short_study_program;

    public function __construct(
        public string $study_program,
        public int $total
    ) {
        $this->short_study_program = Str::limit($study_program, 21, '...');
    }
}
