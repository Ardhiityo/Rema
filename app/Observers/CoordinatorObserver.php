<?php

namespace App\Observers;

use App\Models\Coordinator;
use Illuminate\Support\Facades\Cache;

class CoordinatorObserver
{
    /**
     * Handle the Coordinator "created" event.
     */
    public function created(Coordinator $coordinator): void
    {
        Cache::forget('coordinator.all');
    }

    /**
     * Handle the Coordinator "updated" event.
     */
    public function updated(Coordinator $coordinator): void
    {
        Cache::forget('coordinator.all');
    }

    /**
     * Handle the Coordinator "deleted" event.
     */
    public function deleted(Coordinator $coordinator): void
    {
        Cache::forget('coordinator.all');
    }

    /**
     * Handle the Coordinator "restored" event.
     */
    public function restored(Coordinator $coordinator): void
    {
        Cache::forget('coordinator.all');
    }

    /**
     * Handle the Coordinator "force deleted" event.
     */
    public function forceDeleted(Coordinator $coordinator): void
    {
        Cache::forget('coordinator.all');
    }
}
