<?php

namespace App\Repositories\Eloquent;

use App\Models\Author;
use App\Data\Author\AuthorData;
use App\Data\Author\CreateAuthorData;
use App\Data\Author\UpdateAuthorData;
use App\Repositories\Contratcs\AuthorRepositoryInterface;

class AuthorRepository implements AuthorRepositoryInterface
{
    public function create(CreateAuthorData $create_author_data): AuthorData
    {
        $author = Author::create([
            'nim' => $create_author_data->nim,
            'user_id' => $create_author_data->user_id,
            'study_program_id' => $create_author_data->study_program_id,
            'status' => $create_author_data->status
        ]);

        return AuthorData::fromModel($author);
    }

    public function findById(int $author_id): AuthorData
    {
        $author = Author::findOrFail($author_id);

        return AuthorData::fromModel($author);
    }

    public function update($author_id, UpdateAuthorData $update_author_data): AuthorData
    {
        $author = Author::findOrFail($author_id);

        $author->update([
            'nim' => $update_author_data->nim,
            'study_program_id' => $update_author_data->study_program_id,
            'status' => $update_author_data->status
        ]);

        return AuthorData::fromModel($author->refresh());
    }
}
