<?php

namespace App\Livewire;

use App\Models\Author;
use Livewire\Component;
use App\Models\StudyProgram;

class MetricCard extends Component
{
    public function getAuthorsCountProperty()
    {
        return Author::count();
    }

    public function getMetricsProperty()
    {
        return StudyProgram::with(['authors.metadata'])
            ->get()
            ->map(function ($program) {
                $total = $program->authors->sum(function ($author) {
                    return $author->metadata()->count();
                });
                return [
                    'program' => $program->name,
                    'total_repositories' => $total,
                ];
            });
    }

    public function render()
    {
        return view('livewire.metric-card');
    }
}
