<?php

namespace App\Repositories\Eloquent;

use Throwable;
use App\Models\Activity;
use App\Data\Activity\CreateActivityData;
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
            logger($th->getMessage());
        }
    }
}
