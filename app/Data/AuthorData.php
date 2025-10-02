<?php

namespace App\Data;

use App\Models\Author;
use Livewire\Attributes\Computed;
use Spatie\LaravelData\Data;

class AuthorData extends Data
{
    #[Computed]
    public string $badge_status;
    #[Computed]
    public string $ucfirst_status;

    public function __construct(
        public int $author_id,
        public int $user_id,
        public string|null $nim,
        public string $email,
        public string $name,
        public int|null $study_program_id,
        public string|null $study_program_name,
        public string $status,
        public string $origin_status
    ) {
        $this->badge_status = $origin_status === 'approve' ? 'badge bg-success' : 'badge bg-danger';
        $this->ucfirst_status = ucfirst($status);
    }

    public static function fromModel(Author $author)
    {
        return new self(
            $author->id,
            $author->user->id,
            $author?->nim,
            $author->user->email,
            $author->user->name,
            $author?->studyProgram?->id,
            $author?->studyProgram?->name,
            $author->status,
            $author->status,
        );
    }
}
