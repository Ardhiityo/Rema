<?php

declare(strict_types=1);

namespace App\Data\Metadata;

use App\Models\MetaData;
use Spatie\LaravelData\Data;
use App\Data\Activity\ActivityReportData;

class MetadataReportData extends Data
{
    public function __construct(
        public string $title,
        public string $author,
        public string|int $nim,
        public string $study_program,
        public ActivityReportData $activities,
        public string|int $total_views
    ) {}

    public static function fromModel(MetaData $meta_data): self
    {
        return new self(
            $meta_data->title,
            $meta_data->author_name,
            $meta_data->author_nim,
            $meta_data->author_study_program,
            ActivityReportData::fromActivities($meta_data->activities),
            $meta_data->activities->count(),
        );
    }
}
