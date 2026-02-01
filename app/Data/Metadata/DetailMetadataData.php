<?php

declare(strict_types=1);

namespace App\Data\Metadata;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use App\Data\Category\CategoryData;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;

class DetailMetadataData extends Data
{
    #[Computed()]
    public string $badge_status_class = '';
    public string $badge_ucfirst = '';

    public function __construct(
        public int|string $id,
        public string $title,
        public string $visibility,
        public int|string $year,
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

    public static function fromModel(\App\Models\Metadata $meta_data): self
    {
        return new self(
            $meta_data->id,
            $meta_data->title,
            $meta_data->visibility,
            $meta_data->year,
            $meta_data->slug,
            $meta_data->status,
            Carbon::parse($meta_data->created_at)->format('d F Y'),
            CategoryData::collect($meta_data->categories, DataCollection::class),
            $meta_data->author_name,
            $meta_data->author_nim,
            $meta_data?->author?->user?->avatar ? Storage::url($meta_data->author->user->avatar) : asset('assets/compiled/jpg/anonym.jpg'),
            $meta_data->studyProgram->name
        );
    }
}
