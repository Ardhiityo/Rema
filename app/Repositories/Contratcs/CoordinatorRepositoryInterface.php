<?php

namespace App\Repositories\Contratcs;

use App\Data\Coordinator\CoordinatorData;
use Illuminate\Pagination\LengthAwarePaginator;

interface CoordinatorRepositoryInterface
{
    public function findByFilters(string $keyword): LengthAwarePaginator;
}
