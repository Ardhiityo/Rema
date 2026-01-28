<?php

declare(strict_types=1);

namespace App\Data\LandingPage;

use App\Models\MetaData;
use Spatie\LaravelData\Data;

class SearchHeroData extends Data
{
    public function __construct(
        public string $name,
        public string $nim,
        public string $study_program,
        public string $title,
        public string $metadata_slug,
        public string $category_slug,
        public string $category_name,
        public string $year,
        public int|string $views
    ) {}

    public static function fromModel(MetaData $meta_data): self
    {
        return new self(
            $meta_data->author_name,
            $meta_data->author_nim,
            $meta_data->author_study_program,
            $meta_data->title,
            $meta_data->slug,
            $meta_data->categories->first()->slug,
            $meta_data->categories->first()->name,
            $meta_data->year,
            $meta_data->activities_count
        );
    }
}
