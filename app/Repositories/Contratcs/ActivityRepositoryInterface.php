<?php

namespace App\Repositories\Contratcs;

use App\Data\Activity\CreateActivityData;

interface ActivityRepositoryInterface
{
    public function create(CreateActivityData $create_activity_data);
}
