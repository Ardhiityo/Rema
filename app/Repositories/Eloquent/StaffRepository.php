<?php

namespace App\Repositories\Eloquent;

use Throwable;
use App\Models\Staff;
use App\Data\Staff\StaffData;
use App\Data\Staff\StaffListData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Data\Staff\CreateStaffData;
use App\Data\Staff\UpdateStaffData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contratcs\StaffRepositoryInterface;

class StaffRepository implements StaffRepositoryInterface
{
    public function create(CreateStaffData $create_staff_data): StaffData
    {
        try {
            $staff = Staff::create([
                'user_id' => $create_staff_data->user_id,
                'faculty_id' => $create_staff_data->faculty_id,
            ]);

            return StaffData::fromModel($staff);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'StaffRepository',
                        'method' => 'create',
                    ],
                    'data' => [
                        'create_staff_data' => $create_staff_data,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function findById(int $staff_id, array $relations = []): StaffData|Throwable
    {
        try {
            $staff = Staff::findOrFail($staff_id);

            if (!empty($relations)) {
                $staff = $staff->load($relations);
            }

            return StaffData::fromModel($staff);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'StaffRepository',
                        'method' => 'findById',
                    ],
                    'data' => [
                        'staff_id' => $staff_id,
                        'relations' => $relations,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function update($staff_id, UpdateStaffData $update_staff_data): StaffData|Throwable
    {
        try {
            $staff = Staff::findOrFail($staff_id);

            $staff->update([
                'faculty_id' => $update_staff_data->faculty_id
            ]);

            return StaffData::fromModel($staff->refresh());
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'StaffRepository',
                        'method' => 'update',
                    ],
                    'data' => [
                        'staff_id' => $staff_id,
                        'update_staff_data' => $update_staff_data,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function findByFilters(string|null $keyword = null, string|null $faculty_slug = null): LengthAwarePaginator
    {
        $query = Staff::query();

        if ($keyword) {
            $query->whereHas(
                'user',
                fn($query) => $query->whereLike('name', "%$keyword%")
            );
        }

        if ($faculty_slug) {
            $query->whereHas(
                'faculty',
                fn($query) => $query->where('slug', $faculty_slug)
            );
        }

        return StaffListData::collect($query->orderByDesc('id')->paginate(10));
    }
}
