<?php

namespace App\Repositories\Eloquent;

use Throwable;
use App\Models\Coordinator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelData\DataCollection;
use App\Data\Coordinator\CoordinatorData;
use App\Data\Coordinator\CreateCoordinatorData;
use App\Data\Coordinator\UpdateCoordinatorData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contratcs\CoordinatorRepositoryInterface;

class CoordinatorRepository implements CoordinatorRepositoryInterface
{
    public function all(): DataCollection
    {
        return Cache::rememberForever('coordinator.all', function () {
            return CoordinatorData::collect(
                Coordinator::orderByDesc('id')->get(),
                DataCollection::class
            );
        });
    }

    public function findByNidn(int $nidn): CoordinatorData|Throwable
    {
        try {
            $coordinator = Coordinator::where('nidn', $nidn)->firstOrFail();

            return CoordinatorData::fromModel($coordinator);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'CoordinatorRepository',
                        'method' => 'findByNidn',
                    ],
                    'data' => [
                        'nidn' => $nidn,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function findById(int $coordinator_id): CoordinatorData|Throwable
    {
        try {
            $coordinator = Coordinator::findOrFail($coordinator_id);

            return CoordinatorData::fromModel($coordinator);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'CoordinatorRepository',
                        'method' => 'findById',
                    ],
                    'data' => [
                        'coordinator_id' => $coordinator_id,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

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

    public function create(CreateCoordinatorData $create_coordinator_data): CoordinatorData|Throwable
    {
        try {
            $coordinator = Coordinator::create([
                'name' => $create_coordinator_data->name,
                'nidn' => $create_coordinator_data->nidn,
                'position' => $create_coordinator_data->position,
                'study_program_id' => $create_coordinator_data->study_program_id
            ]);

            return CoordinatorData::fromModel($coordinator);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'CoordinatorRepository',
                        'method' => 'create',
                    ],
                    'data' => [
                        'create_coordinator_data' => $create_coordinator_data,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function update(int $coordinator_id, UpdateCoordinatorData $updateCoordinatorData): CoordinatorData |Throwable
    {
        try {
            $coordinator = Coordinator::findOrFail($coordinator_id);

            $coordinator->update([
                'name' =>  $updateCoordinatorData->name,
                'nidn' => $updateCoordinatorData->nidn,
                'position' => $updateCoordinatorData->position,
                'study_program_id' => $updateCoordinatorData->study_program_id
            ]);

            return CoordinatorData::fromModel($coordinator->refresh());
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'CoordinatorRepository',
                        'method' => 'update',
                    ],
                    'data' => [
                        'update_coordinator_data' => $updateCoordinatorData,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function delete(int $coordinator_id): bool|Throwable
    {
        try {
            $coordinator = Coordinator::findOrFail($coordinator_id);

            return $coordinator->delete();
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'CoordinatorRepository',
                        'method' => 'delete',
                    ],
                    'data' => [
                        'coordinator_id' => $coordinator_id,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));
            throw $th;
        }
    }
}
