<?php

namespace App\Repositories\Contratcs;

use Spatie\LaravelData\DataCollection;
use App\Data\StudyProgram\StudyProgramData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Data\StudyProgram\CreateStudyProgramData;
use App\Data\StudyProgram\UpdateStudyProgramData;
use Throwable;

interface StudyProgramRepositoryInterface
{
    public function create(CreateStudyProgramData $create_study_program_data): StudyProgramData|Throwable;

    public function findById(int $study_program_id): StudyProgramData|Throwable;

    public function update(int $study_program_id, UpdateStudyProgramData $update_study_program_data): StudyProgramData|Throwable;

    public function delete(int $study_program_id): bool|Throwable;

    public function all(): DataCollection;

    public function findByFilters(string|null $keyword = null): LengthAwarePaginator;
}
