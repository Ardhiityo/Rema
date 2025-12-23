<?php

declare(strict_types=1);

namespace App\Data\Activity;

use Spatie\LaravelData\Data;
use Illuminate\Support\Collection;
use Spatie\LaravelData\DataCollection;
use App\Data\Activity\ActivityCategoryData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use App\Repositories\Contratcs\CategoryRepositoryInterface;

class ActivityReportData extends Data
{
    public function __construct(
        #[DataCollectionOf(ActivityCategoryData::class)] public DataCollection $items
    ) {}

    private static $cachedCategories = null;

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
        if (is_null(self::$cachedCategories)) {
            self::$cachedCategories = app(
                CategoryRepositoryInterface::class
            )->all()->toCollection();
        }

        return self::$cachedCategories;
    }
}
