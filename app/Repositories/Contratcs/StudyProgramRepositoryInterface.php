<?php

namespace App\Repositories\Contratcs;

use App\Data\StudyProgram\StudyProgramData;
use App\Data\StudyProgram\CreateStudyProgramData;
use App\Data\StudyProgram\UpdateStudyProgramData;

interface StudyProgramRepositoryInterface
{
    public function create(CreateStudyProgramData $create_study_program_data): StudyProgramData;

    public function findById(int $study_program_id): StudyProgramData;

    public function update($study_program_id, UpdateStudyProgramData $update_study_program_data): StudyProgramData;

    public function delete(int $study_program_id): bool;
}
