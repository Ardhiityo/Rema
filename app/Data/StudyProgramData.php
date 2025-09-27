<?php

namespace App\Data;

use Illuminate\Support\Str;
use App\Models\StudyProgram;
use Spatie\LaravelData\Data;

class StudyProgramData extends Data
{
    public function __construct(
        public int|null $id = null,
        public string $name,
        public string|null $slug = null,
        public string|null $created_at = null
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

    public function getSlug()
    {
        return Str::slug($this->name);
    }
}
