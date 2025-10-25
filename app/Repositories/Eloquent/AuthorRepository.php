<?php

namespace App\Repositories\Eloquent;

use Throwable;
use App\Models\Author;
use App\Data\Author\AuthorData;
use App\Data\Author\AuthorListData;
use App\Data\Author\AuthorReportData;
use App\Data\Author\CreateAuthorData;
use App\Data\Author\UpdateAuthorData;
use Spatie\LaravelData\DataCollection;
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
                'status' => $create_author_data->status
            ]);

            return AuthorData::fromModel($author);
        } catch (Throwable $th) {
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
            throw $th;
        }
    }

    public function update($author_id, UpdateAuthorData $update_author_data): AuthorData|Throwable
    {
        try {
            $author = Author::findOrFail($author_id);

            $author->update([
                'nim' => $update_author_data->nim,
                'study_program_id' => $update_author_data->study_program_id,
                'status' => $update_author_data->status
            ]);

            return AuthorData::fromModel($author->refresh());
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function findByApprovals(array|null $relations = null): DataCollection
    {
        $authors = Author::query();

        if ($relations) {
            $authors->with($relations);
        }

        $authors->approve();

        return AuthorListData::collect(
            $authors->orderByDesc('id')->get(),
            DataCollection::class
        );
    }

    public function findByFilters(string $status_filter = 'approve', string|null $keyword = null, string|null $study_program_slug = null): LengthAwarePaginator
    {
        $query = Author::query();

        $query->where('status', $status_filter);

        if ($keyword) {
            $query->whereHas(
                'user',
                fn($query) => $query->whereLike('name', "%$keyword%")
            )
                ->orWhere('nim', $keyword);
        }

        if ($study_program_slug) {
            $query->whereHas('studyProgram', fn($query) => $query->where('slug', $study_program_slug));
        }

        return AuthorListData::collect($query->orderByDesc('id')->paginate(10));
    }

    public function reports(int|string $year, array $includes = []): DataCollection
    {
        $authors = Author::query()
            ->with([
                'metadata.categories' => function ($query) use ($includes) {
                    if (!empty($includes)) {
                        $query->whereIn('slug', $includes);
                    }
                },
                'studyProgram',
                'user'
            ])
            ->where('status', 'approve');

        if (empty($includes)) {
            $authors = $authors
                ->whereDoesntHave('metadata')
                ->orWhereHas(
                    'metadata',
                    fn($query) => $query
                        ->where('year', $year)
                        ->where('status', '!=', 'approve')
                        ->whereDoesntHave('categories')
                );
        }

        if (!empty($includes)) {
            $authors = $authors->whereHas('metadata', function ($query) use ($year, $includes) {
                $query
                    ->where('year', $year)
                    ->where('status', 'approve')
                    ->whereHas(
                        'categories',
                        fn($query) => $query->whereIn('slug', $includes)
                    );
            });
        }

        $authors = $authors->orderBy('nim', 'asc')->get();

        return AuthorReportData::collect($authors, DataCollection::class);
    }

    public function findByNameOrNim(string $keyword = ''): DataCollection
    {
        $authors = Author::query()->with('user');

        if ($keyword) {
            $authors = $authors
                ->where('nim', $keyword)
                ->orWhereHas(
                    'user',
                    fn($query) => $query->whereLike('name', "%$keyword%")
                );
        }

        return AuthorListData::collect(
            $authors->orderBy('nim')->limit(5)->get(),
            DataCollection::class
        );
    }
}
