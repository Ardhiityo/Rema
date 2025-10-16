<?php

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
        public string $category_slug,
        public string $metadata_slug,
        public string $category_name,
        public string $created_at
    ) {}

    public static function fromModel(MetaData $meta_data): self
    {
        return new self(
            $meta_data->author->user->name,
            $meta_data->author->nim,
            $meta_data->author->studyProgram->name,
            $meta_data->title,
            $meta_data->categories->first()->slug,
            $meta_data->slug,
            $meta_data->categories->first()->name,
            $meta_data->created_at->format('d F Y')
        );
    }
}
