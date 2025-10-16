<?php

namespace App\Repositories\Contratcs;

use App\Data\Author\AuthorData;
use App\Data\Author\CreateAuthorData;
use App\Data\Author\UpdateAuthorData;
use Spatie\LaravelData\DataCollection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AuthorRepositoryInterface
{
    public function create(CreateAuthorData $create_author_data): AuthorData;

    public function findById(int $author_id): AuthorData;

    public function update($author_id, UpdateAuthorData $update_author_data): AuthorData;

    public function findByApprovals(array|null $relations = null): DataCollection;

    public function findByFilters(string $status_filter = 'approve', string|null $keyword = null): LengthAwarePaginator;
}
