<?php

namespace App\Repositories\Eloquent;

use App\Data\Coordinator\CoordinatorData;
use App\Models\Coordinator;
use App\Repositories\Contratcs\CoordinatorRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CoordinatorRepository implements CoordinatorRepositoryInterface
{
    public function findByFilters(string $keyword): LengthAwarePaginator
    {
        $coordinators = Coordinator::query();

        if ($keyword) {
            $coordinators->whereLike('name', "%$keyword%")
                ->orWhere('nidn', $keyword);
        }

        return CoordinatorData::collect(
            $coordinators->orderByDesc('id')->paginate(10)
        );
    }
}
