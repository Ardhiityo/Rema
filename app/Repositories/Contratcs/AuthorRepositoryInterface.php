<?php

namespace App\Repositories\Contratcs;

use App\Data\Author\AuthorData;
use App\Data\Author\CreateAuthorData;
use App\Data\Author\UpdateAuthorData;
use Spatie\LaravelData\DataCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

interface AuthorRepositoryInterface
{
    public function create(CreateAuthorData $create_author_data): AuthorData|Throwable;

    public function findById(int $author_id, array $relation = []): AuthorData|Throwable;

    public function update($author_id, UpdateAuthorData $update_author_data): AuthorData|Throwable;

    public function findByApprovals(array|null $relations = null): DataCollection;

    public function findByFilters(
        string $status_filter = 'approve',
        string|null $keyword = null,
        string|null $study_program_slug = null
    ): LengthAwarePaginator;

    public function reports(string|int $year, array $includes = [], int $coordinator_id): DataCollection;

    public function findByNameOrNim(string $keyword = ''): DataCollection;
}
