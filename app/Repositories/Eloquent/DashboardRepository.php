<?php

namespace App\Repositories\Eloquent;

use App\Models\MetaData;
use App\Data\User\UserData;
use App\Models\StudyProgram;
use App\Data\RecentlyAddData;
use App\Data\Metric\MetricData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\DataCollection;
use App\Repositories\Contratcs\DashboardRepositoryInterface;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function metricCards(): DataCollection
    {
        $rows = MetaData::query()
            ->join('authors', 'meta_data.author_id', '=', 'authors.id')
            ->join('study_programs', 'authors.study_program_id', '=', 'study_programs.id')
            ->where('meta_data.status', 'approve')
            ->where('authors.status', 'approve')
            ->groupBy('study_programs.id', 'study_programs.name')
            ->select('study_programs.id', 'study_programs.name', DB::raw('count(meta_data.id) as total'))
            ->get();

        // Pastikan semua study program muncul (termasuk yang 0): gabungkan dengan StudyProgram::get() bila perlu
        $studyPrograms = StudyProgram::get()->keyBy('id');

        $metrics = $studyPrograms->map(function ($sp) use ($rows) {
            $row = $rows->firstWhere('id', $sp->id);
            return [
                'study_program' => $sp->name,
                'total' => $row ? (int) $row->total : 0,
            ];
        })->values();

        return MetricData::collect($metrics, DataCollection::class);
    }

    public function charts(): array
    {
        $repositories = MetaData::where('status', 'approve')->select('year', DB::raw('count(*) as total_repositories'))
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->limit(3)
            ->get();

        $repository_years = [];
        $repository_totals = [];

        foreach ($repositories as $key => $repository) {
            $repository_years[] = $repository->year;
            $repository_totals[] = $repository->total_repositories;
        }

        $recently_adds = RecentlyAddData::collect(MetaData::with(['author', 'author.user'])
            ->where('status', 'approve')
            ->limit(3)
            ->orderByDesc('id')
            ->get());

        $user_logged = UserData::fromModel(Auth::user());

        $latest_repositories = RecentlyAddData::collect(
            MetaData::with('author', 'author.user', 'categories')
                ->where('status', 'approve')
                ->limit(5)
                ->orderByDesc('id')
                ->get()
        );

        return compact(
            'repository_years',
            'repository_totals',
            'recently_adds',
            'user_logged',
            'latest_repositories'
        );
    }
}
