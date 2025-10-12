<?php

namespace App\Repositories\Contratcs;

use App\Data\Author\AuthorData;
use App\Data\Author\CreateAuthorData;
use App\Data\Author\UpdateAuthorData;

interface AuthorRepositoryInterface
{
    public function create(CreateAuthorData $create_author_data): AuthorData;

    public function findById(int $author_id): AuthorData;

    public function update($author_id, UpdateAuthorData $update_author_data): AuthorData;
}
