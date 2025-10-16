<?php

namespace App\Data\User;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class CreateUserData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public UploadedFile|null $avatar
    ) {}
}
