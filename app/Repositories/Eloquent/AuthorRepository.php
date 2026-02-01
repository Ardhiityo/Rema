<?php

namespace App\Repositories\Eloquent;

use Throwable;
use App\Models\Author;
use App\Data\Author\AuthorData;
use App\Data\Author\AuthorListData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Data\Author\CreateAuthorData;
use App\Data\Author\UpdateAuthorData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contratcs\AuthorRepositoryInterface;

class AuthorRepository implements AuthorRepositoryInterface
{
    public function create(CreateAuthorData $create_author_data): AuthorData
    {
        try {
            $author = Author::create([
                'nim' => $create_author_data->nim,
                'user_id' => $create_author_data->user_id,
                'study_program_id' => $create_author_data->study_program_id,
            ]);

            return AuthorData::fromModel($author);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'AuthorRepository',
                        'method' => 'create',
                    ],
                    'data' => [
                        'create_author_data' => $create_author_data,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function findById(int $author_id, array $relations = []): AuthorData|Throwable
    {
        try {
            $author = Author::findOrFail($author_id);

            if (!empty($relations)) {
                $author = $author->load($relations);
            }

            return AuthorData::fromModel($author);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'AuthorRepository',
                        'method' => 'findById',
                    ],
                    'data' => [
                        'author_id' => $author_id,
                        'relations' => $relations,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function update($author_id, UpdateAuthorData $update_author_data): AuthorData|Throwable
    {
        try {
            $author = Author::findOrFail($author_id);

            $author->update([
                'nim' => $update_author_data->nim,
                'study_program_id' => $update_author_data->study_program_id
            ]);

            return AuthorData::fromModel($author->refresh());
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'AuthorRepository',
                        'method' => 'update',
                    ],
                    'data' => [
                        'author_id' => $author_id,
                        'update_author_data' => $update_author_data,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function findByFilters(string|null $keyword = null, string|null $study_program_slug = null): LengthAwarePaginator
    {
        $query = Author::query();

        if ($keyword) {
            $query->whereHas(
                'user',
                fn($query) => $query->whereLike('name', "%$keyword%")
            )->orWhere('nim', $keyword);
        }

        if ($study_program_slug) {
            $query->whereHas(
                'studyProgram',
                fn($query) => $query->where('slug', $study_program_slug)
            );
        }

        return AuthorListData::collect($query->orderByDesc('id')->paginate(10));
    }

    public function authorCount(): int
    {
        return Cache::rememberForever(
            'author.count',
            fn() => Author::count()
        );
    }
}
