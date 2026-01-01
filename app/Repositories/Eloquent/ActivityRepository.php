<?php

namespace App\Repositories\Eloquent;

use Throwable;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use App\Data\Activity\ActivityData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Data\Activity\ActivityDetailData;
use App\Data\Activity\CreateActivityData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contratcs\ActivityRepositoryInterface;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function create(CreateActivityData $create_activity_data)
    {
        try {
            Activity::create([
                'ip' => $create_activity_data->ip,
                'user_agent' => $create_activity_data->user_agent,
                'user_id' => $create_activity_data->user_id,
                'meta_data_id' => $create_activity_data->meta_data_id,
                'category_id' => $create_activity_data->category_id,
            ]);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'ActivityRepository',
                        'method' => 'create',
                    ],
                    'data' => [
                        'create_activity_data' => $create_activity_data,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function all(): LengthAwarePaginator
    {
        $activities = Activity::query();

        $activities->with(['user']);

        $activities = $activities->select('meta_data_id', 'category_id', DB::raw('COUNT(*) AS total'))
            ->groupBy('meta_data_id', 'category_id')->paginate(10);

        return ActivityData::collect($activities);
    }

    public function findByFilters(string $meta_data_title, string $category_slug, string $sort_by): LengthAwarePaginator
    {
        $activities = Activity::query()
            ->with(['user'])
            ->select('meta_data_id', 'category_id', DB::raw('COUNT(*) AS total'))
            ->groupBy('meta_data_id', 'category_id');

        if ($meta_data_title) {
            $activities = $activities->whereHas(
                'metadata',
                fn($query) => $query->whereLike('title', "%$meta_data_title%")
            );
        }

        if ($category_slug) {
            $activities = $activities->whereHas(
                'category',
                fn($query) => $query->where('slug', $category_slug)
            );
        }

        if ($sort_by == 'popular') {
            $activities = $activities->orderBy('total', 'desc');
        }

        if ($sort_by == 'unpopular') {
            $activities = $activities->orderBy('total', 'asc');
        }

        $activities = $activities->paginate(10);

        return ActivityData::collect($activities);
    }

    public function findByMetaDataSlugAndCategorySlug(string $category_slug, string $meta_data_slug): LengthAwarePaginator
    {
        $activities = Activity::with(['user.author.studyProgram'])
            ->whereHas(
                'metadata',
                fn($query) => $query->where('slug', $meta_data_slug)
            )->whereHas(
                'category',
                fn($query) => $query->where('slug', $category_slug)
            )
            ->paginate(10);

        return ActivityDetailData::collect($activities);
    }
}
