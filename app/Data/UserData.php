<?php

namespace App\Data;

use App\Models\User;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Storage;

class UserData extends Data
{
    #[Computed()]
    public string $short_email;
    #[Computed()]
    public string $short_name;

    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $password,
        public string|bool $avatar
    ) {
        $this->short_name = Str::limit($name, 12, 'xxx');
        $this->short_email = Str::limit($email, '12', 'xxx');
    }

    public static function fromModel(User $user)
    {
        return new self(
            $user->id,
            $user->name,
            $user->email,
            $user->password,
            $user->avatar ? Storage::url($user->avatar) : false
        );
    }
}
