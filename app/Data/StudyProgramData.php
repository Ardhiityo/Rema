<?php

namespace App\Data;

use App\Models\StudyProgram;
use Spatie\LaravelData\Data;

class StudyProgramData extends Data
{
    public function __construct(
        public string $name,
        public string $created_at
    ) {}

    public static function fromModel(StudyProgram $study_program)
    {
        return new self(
            $study_program->name,
            $study_program->created_at->format('d F Y')
        );
    }
}
