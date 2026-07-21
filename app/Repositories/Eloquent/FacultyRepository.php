<?php

namespace App\Repositories\Eloquent;

use App\Data\Faculty\CreateFacultyData;
use App\Data\Faculty\FacultyData;
use App\Data\Faculty\UpdateFacultyData;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Repositories\Contratcs\FacultyRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\DataCollection;
use Throwable;

class FacultyRepository implements FacultyRepositoryInterface
{
    public function create(CreateFacultyData $create_faculty_data): FacultyData|Throwable
    {
        try {
            $faculty = Faculty::create([
                'name' => ucwords(strtolower(trim($create_faculty_data->name))),
                'slug' => $create_faculty_data->slug,
            ]);

            return FacultyData::fromModel($faculty);
        } catch (Throwable $th) {
            Log::error(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'FacultyRepository',
                        'method' => 'create',
                    ],
                    'data' => [
                        'faculty' => $create_faculty_data,
                    ],
                ],
                'message' => $th->getMessage(),
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function findById(int $faculty_id): FacultyData|Throwable
    {
        try {
            $faculty = Faculty::findOrFail($faculty_id);

            return FacultyData::fromModel($faculty);
        } catch (Throwable $th) {
            Log::error(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'FacultyRepository',
                        'method' => 'findById',
                    ],
                    'data' => [
                        'faculty_id' => $faculty_id,
                    ],
                ],
                'message' => $th->getMessage(),
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function update(int $faculty_id, UpdateFacultyData $update_faculty_data): FacultyData|Throwable
    {
        try {
            $faculty = Faculty::findOrFail($faculty_id);

            $faculty->update([
                'name' => ucwords(strtolower(trim($update_faculty_data->name))),
                'slug' => $update_faculty_data->slug,
            ]);

            return FacultyData::fromModel($faculty->refresh());
        } catch (Throwable $th) {
            Log::error(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'FacultyRepository',
                        'method' => 'update',
                    ],
                    'data' => [
                        'faculty_id' => $faculty_id,
                        'update_faculty_data' => $update_faculty_data,
                    ],
                ],
                'message' => $th->getMessage(),
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function delete(int $faculty_id): bool|Throwable
    {
        try {
            $faculty = Faculty::findOrFail($faculty_id);

            $study_programs = StudyProgram::with(['authors.user', 'authors.metadata', 'authors.metadata.categories'])
                ->where('faculty_id', $faculty->id)->get();

            foreach ($study_programs as $study_program) {
                if ($study_program->authors->isNotEmpty()) {
                    foreach ($study_program->authors as $author) {
                        $avatar = $author->user->avatar ?? null;

                        if ($avatar) {
                            if (Storage::disk('public')->exists($avatar)) {
                                Storage::disk('public')->delete($avatar);
                            }
                        }

                        if ($author->metadata->isNotEmpty()) {
                            foreach ($author->metadata as $data) {
                                if ($data->categories->isNotEmpty()) {
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

                    }
                }
            }

            return $faculty->delete();
        } catch (Throwable $th) {
            Log::error(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'FacultyRepository',
                        'method' => 'delete',
                    ],
                    'data' => [
                        'faculty_id' => $faculty_id,
                    ],
                ],
                'message' => $th->getMessage(),
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function all(): DataCollection
    {
        return Cache::rememberForever('faculty.all', function () {
            return FacultyData::collect(
                Faculty::orderByDesc('id')->get(),
                DataCollection::class
            );
        });
    }

    public function findByFilters(?string $keyword = null): LengthAwarePaginator
    {
        $query = Faculty::query();

        if ($keyword) {
            $query->whereLike('name', "$keyword%");
        }

        return FacultyData::collect(
            $query->orderByDesc('id')->paginate(10)
        );
    }
}
