<?php

namespace App\Observers;

use App\Models\StudyProgram;
use Illuminate\Support\Facades\Cache;

class StudyProgramObserver
{
    /**
     * Handle the StudyProgram "created" event.
     */
    public function created(StudyProgram $studyProgram): void
    {
        Cache::forget('study_program.all');
        Cache::forget('metadata.metricCards');
    }

    /**
     * Handle the StudyProgram "updated" event.
     */
    public function updated(StudyProgram $studyProgram): void
    {
        Cache::forget('study_program.all');
        Cache::forget('metadata.metricCards');
    }

    /**
     * Handle the StudyProgram "deleted" event.
     */
    public function deleted(StudyProgram $studyProgram): void
    {
        Cache::forget('study_program.all');
        Cache::forget('metadata.metricCards');
    }

    /**
     * Handle the StudyProgram "restored" event.
     */
    public function restored(StudyProgram $studyProgram): void
    {
        Cache::forget('study_program.all');
        Cache::forget('metadata.metricCards');
    }

    /**
     * Handle the StudyProgram "force deleted" event.
     */
    public function forceDeleted(StudyProgram $studyProgram): void
    {
        Cache::forget('study_program.all');
        Cache::forget('metadata.metricCards');
    }
}
