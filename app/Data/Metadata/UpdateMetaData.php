<?php

declare(strict_types=1);

namespace App\Data\Metadata;

use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class UpdateMetaData extends Data
{
    #[Computed()]
    public string $title_formatted;

    public function __construct(
        public string $title,
        public int $author_id,
        public string $visibility,
        public string $slug,
        public string $year,
        public string $status
    ) {
        $this->title_formatted = ucfirst(strtolower($title));
    }
}
