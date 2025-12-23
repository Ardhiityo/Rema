<?php

declare(strict_types=1);

namespace App\Data\StudyProgram;

use Spatie\LaravelData\Data;

class UpdateStudyProgramData extends Data
{
    public function __construct(
        public string $name,
        public string $slug,
    ) {}
}
