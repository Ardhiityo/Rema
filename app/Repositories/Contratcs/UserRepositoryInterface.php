<?php

namespace App\Repositories\Contratcs;

use App\Data\User\UpdateUserData;
use App\Data\User\UserData;
use App\Data\User\CreateUserData;
use Throwable;

interface UserRepositoryInterface
{
    public function create(CreateUserData $create_user_data): UserData|Throwable;

    public function findById(int $user_id): UserData|Throwable;

    public function update(int $user_id, UpdateUserData $update_user_data): UserData|Throwable;

    public function delete(int $user_id): bool|Throwable;
}
