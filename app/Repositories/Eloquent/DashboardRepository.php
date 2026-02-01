<?php

namespace App\Repositories\Eloquent;

use App\Models\Metadata;
use App\Data\User\UserData;
use App\Models\StudyProgram;
use App\Data\RecentlyAddData;
use App\Data\Metric\MetricData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelData\DataCollection;
use App\Repositories\Contratcs\DashboardRepositoryInterface;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function metricCards(): DataCollection
    {
        $metrics = Cache::rememberForever('metadata.metricCards', function () {
            $meta_data = Metadata::with('studyProgram')
                ->where('status', 'approve')
                ->groupBy('study_program_id')
                ->select('study_program_id', DB::raw('count(id) as total'))
                ->get();

            $studyPrograms = StudyProgram::get()->keyBy('id');

            return $studyPrograms->map(function ($study_program) use ($meta_data) {
                $row = $meta_data->firstWhere('study_program_id', $study_program->id);
                return [
                    'study_program' => $study_program->name,
                    'total' => $row ? (int) $row->total : 0,
                ];
            })->values();
        });

        return MetricData::collect($metrics, DataCollection::class);
    }

    public function charts(): array
    {
        $repositories = Cache::rememberForever('metadata.repositories.charts', function () {
            return Metadata::where('status', 'approve')
                ->select('year', DB::raw('count(*) as total_repositories'))
                ->groupBy('year')
                ->orderBy('year', 'asc')
                ->limit(3)
                ->get();
        });

        $repository_years = [];
        $repository_totals = [];

        foreach ($repositories as $key => $repository) {
            $repository_years[] = $repository->year;
            $repository_totals[] = $repository->total_repositories;
        }

        $recently_adds = Cache::rememberForever('metadata.repositories.recently_adds', function () {
            return RecentlyAddData::collect(Metadata::with(['author.user'])
                ->where('status', 'approve')
                ->limit(4)
                ->orderByDesc('id')
                ->get());
        });

        $user_logged = UserData::fromModel(Auth::user());

        return compact(
            'repository_years',
            'repository_totals',
            'recently_adds',
            'user_logged'
        );
    }
}
