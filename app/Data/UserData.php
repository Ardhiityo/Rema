<?php

namespace App\Data;

use App\Models\User;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Attributes\Computed;

class UserData extends Data
{
    #[Computed]
    public string $short_name;
    public string $short_email;

    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $password,
        public string $avatar
    ) {
        $this->short_name = Str::limit($name, 15, '...');
        $this->short_email = Str::limit($email, 15, '...');
    }

    public static function fromModel(User $user)
    {
        return new self(
            $user->id,
            $user->name,
            $user->email,
            $user->password,
            is_null($user->avatar) ? asset('assets/compiled/jpg/1.jpg') : Storage::url($user->avatar)
        );
    }
}
