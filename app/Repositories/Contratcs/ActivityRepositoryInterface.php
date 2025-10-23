<?php

namespace App\Repositories\Contratcs;

use App\Data\Activity\CreateActivityData;
use Illuminate\Pagination\LengthAwarePaginator;

interface ActivityRepositoryInterface
{
    public function create(CreateActivityData $create_activity_data);
    public function all(): LengthAwarePaginator;
}
