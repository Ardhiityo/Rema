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
    public string $author_name_formatted;

    #[Computed()]
    public string $author_nim_formatted;

    #[Computed()]
    public int|null $author_id;
    
    #[Computed()]
    public int|null $year_formatted;

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
        $this->title_formatted = trim(ucfirst(strtolower($title)));
        $this->author_name_formatted = trim(ucfirst(strtolower($author_name)));
        $this->author_nim_formatted = trim((string) $author_nim);
        $this->year_formatted = (int) trim((string) $year);
        $user = Auth::user();
        $this->author_id = $user->hasRole('author') ? $user?->author?->id : null;
    }
}
