<?php

namespace App\Repositories\Contratcs;

use App\Data\User\UpdateUserData;
use App\Data\User\UserData;
use App\Data\User\CreateUserData;

interface UserRepositoryInterface
{
    public function create(CreateUserData $create_user_data): UserData;

    public function findById(int $user_id): UserData|null;

    public function update(int $user_id, UpdateUserData $update_user_data): UserData|null;

    public function delete(int $user_id): bool;
}
