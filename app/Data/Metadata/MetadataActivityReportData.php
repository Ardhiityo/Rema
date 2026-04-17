<?php

declare(strict_types=1);

namespace App\Data\Metadata;

use App\Data\Activity\ActivityReportData;
use Spatie\LaravelData\Data;

class MetadataActivityReportData extends Data
{
    public function __construct(
        public string $title,
        public string $author,
        public string|int $nim,
        public string $study_program,
        public ActivityReportData $activities,
        public string|int $total_views
    ) {}

    public static function fromModel(\App\Models\Metadata $meta_data): self
    {
        $activities = ActivityReportData::fromActivities($meta_data->activities);

        return new self(
            $meta_data->title,
            $meta_data->author_name,
            $meta_data->author_nim,
            $meta_data->studyProgram->name,
            $activities,
            $activities->items->toCollection()->sum('total'),
        );
    }
}
