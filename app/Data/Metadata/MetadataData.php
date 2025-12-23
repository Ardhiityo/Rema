<?php

declare(strict_types=1);

namespace App\Data\Metadata;

use App\Models\Author;
use App\Models\MetaData;
use Spatie\LaravelData\Data;
use App\Data\Author\AuthorData;
use App\Data\Category\CategoryData;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\DataCollectionOf;

class MetadataData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public int $author_id,
        public string $visibility,
        public int $year,
        public string $slug,
        public string $status,
        #[DataCollectionOf(CategoryData::class)]
        public DataCollection|null $categories
    ) {}

    public static function fromModel(MetaData $meta_data): self
    {
        return new self(
            $meta_data->id,
            $meta_data->title,
            $meta_data->author_id,
            $meta_data->visibility,
            $meta_data->year,
            $meta_data->slug,
            $meta_data->status,
            CategoryData::collect($meta_data->categories, DataCollection::class)
        );
    }

    public function toModel(): MetaData
    {
        $meta_data = new MetaData();

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
