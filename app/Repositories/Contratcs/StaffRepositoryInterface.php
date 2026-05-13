<?php

namespace App\Repositories\Contratcs;

use Throwable;
use App\Data\Staff\StaffData;
use App\Data\Staff\CreateStaffData;
use App\Data\Staff\UpdateStaffData;
use Illuminate\Pagination\LengthAwarePaginator;

interface StaffRepositoryInterface
{
    public function create(CreateStaffData $create_staff_data): StaffData|Throwable;

    public function findById(int $staff_id, array $relation = []): StaffData|Throwable;

    public function update($staff_id, UpdateStaffData $update_staff_data): StaffData|Throwable;

    public function findByFilters(
        string|null $keyword = null,
        string|null $faculty_slug = null
    ): LengthAwarePaginator;
}
