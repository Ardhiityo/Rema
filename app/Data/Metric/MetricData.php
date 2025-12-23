<?php

declare(strict_types=1);

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
        public int|string $total
    ) {
        $this->short_study_program = Str::limit($study_program, 21, '...');
    }

    public static function fromModel($metrics): self
    {
        return new self(
            $metrics['study_program'],
            $metrics['total']
        );
    }
}
