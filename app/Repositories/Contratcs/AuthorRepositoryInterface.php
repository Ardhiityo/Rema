<?php

namespace App\Repositories\Contratcs;

use Throwable;
use App\Data\Author\AuthorData;
use App\Data\Author\CreateAuthorData;
use App\Data\Author\UpdateAuthorData;
use Illuminate\Pagination\LengthAwarePaginator;

interface AuthorRepositoryInterface
{
    public function create(CreateAuthorData $create_author_data): AuthorData|Throwable;

    public function findById(int $author_id, array $relation = []): AuthorData|Throwable;

    public function update($author_id, UpdateAuthorData $update_author_data): AuthorData|Throwable;

    public function findByFilters(
        string|null $keyword = null,
        string|null $study_program_slug = null
    ): LengthAwarePaginator;

    public function authorCount(): int;
}
