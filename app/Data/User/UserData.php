<?php

declare(strict_types=1);

namespace App\Data\User;

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
        public int|string $id,
        public string $name,
        public string|null $email,
        public string|null $password,
        public string|null $avatar
    ) {
        $this->short_name = Str::limit($name, 12, 'xxx');
        $this->short_email = Str::limit($email, 12, 'xxx');
    }

    public static function fromModel(User $user)
    {
        return new self(
            $user->id,
            $user->name,
            $user->email,
            $user->password,
            $user->avatar ? Storage::url($user->avatar) : asset('assets/compiled/jpg/anonym.jpg')
        );
    }
}
