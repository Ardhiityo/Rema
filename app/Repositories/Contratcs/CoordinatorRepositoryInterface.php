<?php

namespace App\Repositories\Contratcs;

use App\Data\Coordinator\CoordinatorData;
use App\Data\Coordinator\CreateCoordinatorData;
use App\Data\Coordinator\UpdateCoordinatorData;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\DataCollection;
use Throwable;

interface CoordinatorRepositoryInterface
{
    public function all(): DataCollection;
    public function findById(int $coordinator_id): CoordinatorData|Throwable;
    public function findByNidn(int $nidn): CoordinatorData|Throwable;
    public function delete(int $coordinator_id): bool|Throwable;
    public function findByFilters(string $keyword): LengthAwarePaginator;
    public function create(CreateCoordinatorData $create_coordinator_data): CoordinatorData|Throwable;
    public function update(int $coordinator_id, UpdateCoordinatorData $updateCoordinatorData): CoordinatorData|Throwable;
}
