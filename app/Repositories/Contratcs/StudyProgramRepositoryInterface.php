<?php

namespace App\Repositories\Contratcs;

use Spatie\LaravelData\DataCollection;
use App\Data\StudyProgram\StudyProgramData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Data\StudyProgram\CreateStudyProgramData;
use App\Data\StudyProgram\UpdateStudyProgramData;

interface StudyProgramRepositoryInterface
{
    public function create(CreateStudyProgramData $create_study_program_data): StudyProgramData;

    public function findById(int $study_program_id): StudyProgramData|null;

    public function update($study_program_id, UpdateStudyProgramData $update_study_program_data): StudyProgramData|null;

    public function delete(int $study_program_id): bool;

    public function all(): DataCollection;

    public function findByFilters(string|null $keyword = null): LengthAwarePaginator;
}
