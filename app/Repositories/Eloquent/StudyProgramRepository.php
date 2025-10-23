<?php

namespace App\Repositories\Eloquent;

use Throwable;
use App\Models\Author;
use App\Models\StudyProgram;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Facades\Storage;
use App\Data\StudyProgram\StudyProgramData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Data\StudyProgram\CreateStudyProgramData;
use App\Data\StudyProgram\UpdateStudyProgramData;
use App\Repositories\Contratcs\StudyProgramRepositoryInterface;

class StudyProgramRepository implements StudyProgramRepositoryInterface
{
    public function create(CreateStudyProgramData $create_study_program_data): StudyProgramData|Throwable
    {
        try {
            $study_program = StudyProgram::create([
                'name' => ucwords(strtolower($create_study_program_data->name)),
                'slug' => $create_study_program_data->slug
            ]);

            return StudyProgramData::fromModel($study_program);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function findById(int $study_program_id): StudyProgramData|Throwable
    {
        try {
            $study_program = StudyProgram::findOrFail($study_program_id);

            return StudyProgramData::fromModel($study_program);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function update($study_program_id, UpdateStudyProgramData $update_study_program_data): StudyProgramData|Throwable
    {
        try {
            $study_program = StudyProgram::findOrFail($study_program_id);

            $study_program->update([
                'name' => $update_study_program_data->name,
                'slug' => $update_study_program_data->slug
            ]);

            return StudyProgramData::fromModel($study_program->refresh());
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function delete(int $study_program_id): bool|Throwable
    {
        try {
            $study_program = StudyProgram::findOrFail($study_program_id);

            $authors = Author::with(['user', 'metadata', 'metadata.categories'])
                ->where('study_program_id', $study_program->id)->get();

            foreach ($authors as $author) {
                $avatar = $author->user->avatar ?? null;

                if ($avatar) {
                    if (Storage::disk('public')->exists($avatar)) {
                        Storage::disk('public')->delete($avatar);
                    }
                }

                if ($author->metadata->isNotEmpty()) {
                    if ($author->metadata->categories->isNotEmpty()) {
                        foreach ($author->metadata as $data) {
                            foreach ($data->categories as $category) {
                                $file_path = $category->pivot->file_path ?? null;
                                if ($file_path) {
                                    if (Storage::disk('public')->exists($file_path)) {
                                        Storage::disk('public')->delete($file_path);
                                    }
                                }
                            }
                        }
                    }
                }

                $author->user()->delete();
            }

            return $study_program->delete();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function all(): DataCollection
    {
        return StudyProgramData::collect(StudyProgram::orderByDesc('id')->get(), DataCollection::class);
    }

    public function findByFilters(string|null $keyword = null): LengthAwarePaginator
    {
        $query = StudyProgram::query();

        if ($keyword) {
            $query->whereLike('name', "%$keyword%");
        }

        return StudyProgramData::collect(
            $query->orderByDesc('id')->paginate(10)
        );
    }
}
