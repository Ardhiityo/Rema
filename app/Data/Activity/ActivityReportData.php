<?php

namespace App\Data\Activity;

use Spatie\LaravelData\Data;
use Illuminate\Support\Collection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use App\Repositories\Contratcs\CategoryRepositoryInterface;

class ActivityReportData extends Data
{
    public function __construct(
        #[DataCollectionOf(ActivityCategoryData::class)] public DataCollection $items
    ) {}

    public static function fromActivities(Collection $activities): self
    {
        $categories = self::getCategories();

        $items = $categories->map(function ($category) use ($activities) {
            $activity = $activities->firstWhere('category_id', $category->id);

            return ActivityCategoryData::from([
                'category' => $category->name,
                'total' => $activity ? $activity->total : 0
            ]);
        });

        return new self(
            ActivityCategoryData::collect($items, DataCollection::class)
        );
    }

    public static function getCategories()
    {
        return app(CategoryRepositoryInterface::class)->all()->toCollection();
    }
}
