<?php

namespace App\Data\User;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class CreateUserData extends Data
{
    public function __construct(
        public string $name,
        public string|null $email,
        public string|int $nim,
        public string|null $password,
        public null|string|UploadedFile $avatar
    ) {}
}
