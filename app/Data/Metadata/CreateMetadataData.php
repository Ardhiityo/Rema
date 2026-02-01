<?php

declare(strict_types=1);

namespace App\Data\Metadata;

use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Attributes\Computed;

class CreateMetadataData extends Data
{
    #[Computed()]
    public string $title_formatted;

    #[Computed()]
    public int|null $author_id;

    public function __construct(
        public string $title,
        public int|string $author_name,
        public int|string $author_nim,
        public int|string $study_program_id,
        public string $visibility,
        public int|string $year,
        public string $slug,
        public string $status
    ) {
        $this->title_formatted = ucfirst(strtolower($title));
        $user = Auth::user();
        $this->author_id = $user->hasRole('author') ? $user?->author?->id : null;
    }
}
