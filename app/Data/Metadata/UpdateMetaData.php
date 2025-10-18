<?php

namespace App\Data\MetaData;

use Spatie\LaravelData\Data;

class UpdateMetaData extends Data
{
    public function __construct(
        public string $title,
        public int $author_id,
        public string $visibility,
        public string $slug,
        public string $year,
        public string $status
    ) {}
}
