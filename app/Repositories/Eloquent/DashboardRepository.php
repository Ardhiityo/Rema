<?php

namespace App\Repositories\Eloquent;

use App\Models\MetaData;
use App\Models\StudyProgram;
use App\Data\Metric\MetricData;
use Illuminate\Support\Facades\DB;
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
}
