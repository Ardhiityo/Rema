<?php

namespace App\Repositories\Eloquent;

use Throwable;
use App\Models\Author;
use App\Models\Coordinator;
use App\Data\Author\AuthorData;
use App\Data\Author\AuthorListData;
use App\Data\Author\AuthorReportData;
use App\Data\Author\CreateAuthorData;
use App\Data\Author\UpdateAuthorData;
use Illuminate\Support\Facades\Cache;
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
            logger($th->getMessage(), ['Author Repository' => 'update']);
            throw $th;
        }
    }

    public function findByApprovals(array|null $relations = null): DataCollection
    {
        return Cache::rememberForever('author.findByApprovals', function () use ($relations) {
            $authors = Author::query();

            if ($relations) {
                $authors->with($relations);
            }

            $authors->approve();

            return AuthorListData::collect(
                $authors->orderByDesc('id')->get(),
                DataCollection::class
            );
        });
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

    public function reports(int|string $year, array $includes = [], int $coordinator_id): DataCollection
    {
        $coordinator = Coordinator::find($coordinator_id);

        $authors = Author::query()->with([
            'metadata.categories' => function ($query) use ($includes) {
                if (!empty($includes)) {
                    $query->whereIn('slug', $includes);
                }
            },
            'studyProgram',
            'user'
        ])->whereHas(
            'studyProgram',
            fn($query) =>
            $query->where('slug', $coordinator->studyProgram->slug)
        );

        if (empty($includes)) {
            $authors = $authors
                ->whereDoesntHave('metadata')
                ->orWhereHas(
                    'metadata',
                    fn($query) => $query
                        ->where('year', $year)
                        ->where('status', '!=', 'approve')
                        ->whereDoesntHave('categories')
                        ->orWhereHas('categories')
                );
        }

        if (!empty($includes)) {
            $authors = $authors
                ->whereDoesntHave('metadata')
                ->OrwhereHas(
                    'metadata',
                    function ($query) use ($year, $includes) {
                        $query
                            ->where('year', $year)
                            ->whereDoesntHave('categories')
                            ->OrwhereHas(
                                'categories',
                                fn($query) => $query->whereIn('slug', $includes)
                            );
                    }
                );
        }

        $authors = $authors->orderBy('nim', 'asc')->get();

        return AuthorReportData::collect($authors, DataCollection::class);
    }

    public function findByNameOrNim(string $keyword = ''): DataCollection
    {
        $authors = Author::query()
            ->with('user');

        if ($keyword) {
            $authors = $authors
                ->where('nim', $keyword)
                ->orWhereHas(
                    'user',
                    fn($query) => $query->whereLike('name', "%$keyword%")
                );
        }

        $authors = $authors->where('status', 'approve')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return AuthorListData::collect($authors, DataCollection::class);
    }
}
