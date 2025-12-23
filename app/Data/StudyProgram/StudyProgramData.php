<?php

declare(strict_types=1);

namespace App\Data\StudyProgram;

use App\Models\StudyProgram;
use Spatie\LaravelData\Data;

class StudyProgramData extends Data
{
    public function __construct(
        public int|string $id,
        public string $name,
        public string $slug,
        public string $created_at
    ) {}

    public static function fromModel(StudyProgram $study_program)
    {
        return new self(
            $study_program->id,
            $study_program->name,
            $study_program->slug,
            $study_program->created_at->format('d F Y')
        );
    }
}
