<?php

namespace App\Repositories\Eloquent;

use App\Models\StudyProgram;
use App\Data\StudyProgram\StudyProgramData;
use App\Data\StudyProgram\CreateStudyProgramData;
use App\Data\StudyProgram\UpdateStudyProgramData;
use App\Repositories\Contratcs\StudyProgramRepositoryInterface;

class StudyProgramRepository implements StudyProgramRepositoryInterface
{
    public function create(CreateStudyProgramData $create_study_program_data): StudyProgramData
    {
        $study_program = StudyProgram::create([
            'name' => $create_study_program_data->name,
            'slug' => $create_study_program_data->slug
        ]);

        return StudyProgramData::fromModel($study_program);
    }

    public function findById(int $study_program_id): StudyProgramData
    {
        $study_program = StudyProgram::findOrFail($study_program_id);

        return StudyProgramData::fromModel($study_program);
    }

    public function update($study_program_id, UpdateStudyProgramData $update_study_program_data): StudyProgramData
    {
        $study_program = StudyProgram::findOrFail($study_program_id);

        $study_program->update([
            'name' => $update_study_program_data->name,
            'slug' => $update_study_program_data->slug
        ]);

        return StudyProgramData::fromModel($study_program->refresh());
    }

    public function delete(int $study_program_id): bool
    {
        $study_program = StudyProgram::findOrFail($study_program_id);

        return $study_program->delete();
    }
}
