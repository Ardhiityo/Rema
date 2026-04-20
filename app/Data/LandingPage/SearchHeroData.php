<?php

declare(strict_types=1);

namespace App\Data\LandingPage;

use App\Data\Keyword\KeywordData;
use App\Models\Metadata;
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
        public int|string $views,
        public string $keywords
    ) {}

    public static function fromModel(Metadata $meta_data): self
    {
        return new self(
            $meta_data->author_name,
            $meta_data->author_nim,
            $meta_data->studyProgram->name,
            $meta_data->title,
            $meta_data->slug,
            $meta_data->categories->first()->slug,
            $meta_data->categories->first()->name,
            $meta_data->year,
            $meta_data->activities_count,
            $meta_data->keywords->count() > 0 ? implode(', ', KeywordData::collect($meta_data->keywords)->pluck('name')->toArray()) : 'No keywords'
        );
    }
}
