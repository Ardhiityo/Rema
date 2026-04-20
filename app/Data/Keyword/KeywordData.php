<?php

declare(strict_types=1);

namespace App\Data\Keyword;

use App\Models\Keyword;
use Spatie\LaravelData\Data;

class KeywordData extends Data
{
    public function __construct(
        public int|string $id,
        public string $name,
        public string $slug,
        public int|string $meta_data_id
    ) {}

    public static function fromModel(Keyword $keyword): self
    {
        return new self(
            $keyword->id,
            $keyword->name,
            $keyword->slug,
            $keyword->meta_data_id
        );
    }
}
