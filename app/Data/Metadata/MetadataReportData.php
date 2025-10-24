<?php

namespace App\Data\MetaData;

use App\Data\Activity\ActivityReportData;
use App\Models\MetaData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class MetadataReportData extends Data
{
    public function __construct(
        public string $title,
        public string $author,
        public string|int $nim,
        public string $study_program,
        #[DataCollectionOf(ActivityReportData::class)] public DataCollection $activities,
        public string|int $total_views
    ) {}

    public static function fromModel(MetaData $meta_data): self
    {
        return new self(
            $meta_data->title,
            $meta_data->author->user->name,
            $meta_data->author->nim,
            $meta_data->author->studyProgram->name,
            ActivityReportData::collect($meta_data->activities, DataCollection::class),
            $meta_data->activities()->count(),
        );
    }
}
