<?php

namespace App\Observers;

use App\Models\Faculty;
use Illuminate\Support\Facades\Cache;

class FacultyObserver
{
      /**
     * Handle the StudyProgram "created" event.
     */
    public function created(Faculty $faculty): void
    {
        Cache::forget('faculty.all');
    }

    /**
     * Handle the StudyProgram "updated" event.
     */
    public function updated(Faculty $faculty): void
    {
        Cache::forget('faculty.all');
    }

    /**
     * Handle the StudyProgram "deleted" event.
     */
    public function deleted(Faculty $faculty): void
    {
        Cache::forget('faculty.all');
    }

    /**
     * Handle the StudyProgram "restored" event.
     */
    public function restored(Faculty $faculty): void
    {
        Cache::forget('faculty.all');
    }

    /**
     * Handle the StudyProgram "force deleted" event.
     */
    public function forceDeleted(Faculty $faculty): void
    {
        Cache::forget('faculty.all');
    }
}
