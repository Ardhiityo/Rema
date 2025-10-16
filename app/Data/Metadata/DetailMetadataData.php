<?php

namespace App\Data\Metadata;

use App\Models\MetaData;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;
use App\Data\Category\CategoryData;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Attributes\DataCollectionOf;

class DetailMetadataData extends Data
{
    #[Computed()]
    public string $badge_status_class = '';
    public string $badge_ucfirst = '';

    public function __construct(
        public int $id,
        public string $title,
        public int $author_id,
        public string $visibility,
        public int $year,
        public string $slug,
        public string $status,
        public string $created_at,
        #[DataCollectionOf(CategoryData::class)]
        public DataCollection|null $categories,
        public string $name,
        public string $nim,
        public string $avatar,
        public string $study_program
    ) {
        $this->badge_status_class = $status == 'approve' ? 'text-bg-success' : 'text-bg-danger';
        $this->badge_ucfirst = ucfirst($status);
    }

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
            $meta_data->created_at->format('d F Y'),
            CategoryData::collect($meta_data->categories, DataCollection::class),
            $meta_data->author->user->name,
            $meta_data->author->nim,
            Storage::url($meta_data->author->user->avatar),
            $meta_data->author->studyProgram->name
        );
    }
}
