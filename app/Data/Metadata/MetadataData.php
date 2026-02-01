<?php

declare(strict_types=1);

namespace App\Data\Metadata;

use App\Models\Author;
use Spatie\LaravelData\Data;
use App\Data\Author\AuthorData;
use App\Data\Category\CategoryData;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\DataCollectionOf;

class MetadataData extends Data
{
    public function __construct(
        public int|string $id,
        public string $title,
        public int|string|null $author_id,
        public string|null $author_name,
        public int|string|null $author_nim,
        public string|int $study_program_id,
        public string $visibility,
        public int|string $year,
        public string $slug,
        public string $status,
        #[DataCollectionOf(CategoryData::class)]
        public DataCollection|null $categories
    ) {}

    public static function fromModel(\App\Models\Metadata $meta_data): self
    {
        return new self(
            $meta_data->id,
            $meta_data->title,
            $meta_data->author_id,
            $meta_data->author_name,
            $meta_data->author_nim,
            $meta_data->study_program_id,
            $meta_data->visibility,
            $meta_data->year,
            $meta_data->slug,
            $meta_data->status,
            CategoryData::collect($meta_data->categories, DataCollection::class)
        );
    }

    public function toModel(): \App\Models\Metadata
    {
        $meta_data = new \App\Models\Metadata();

        $meta_data->fill([
            'author_id' =>  $this->author_id,
            'status' => $this->status
        ]);

        return $meta_data;
    }

    public function author()
    {
        return AuthorData::fromModel(Author::find($this->author_id));
    }
}
