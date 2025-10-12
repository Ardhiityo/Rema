<?php

namespace App\Data\User;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class UpdateUserData extends Data
{
    public function __construct(
        public string $name,
        public UploadedFile|null|string $avatar,
        public string $email,
        public string|null $password,
    ) {}
}
