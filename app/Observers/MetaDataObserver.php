<?php

namespace App\Observers;

use App\Models\Metadata;
use Illuminate\Support\Facades\Cache;

class MetaDataObserver
{
    /**
     * Handle the MetaData "created" event.
     */
    public function created(Metadata $metadata): void
    {
        Cache::forget('metadata.repositories.charts');
        Cache::forget('metadata.repositories.recently_adds');
        //Cache::forget('metadata.metricCards');
        Cache::forget('metadata.count');
    }

    /**
     * Handle the MetaData "updated" event.
     */
    public function updated(Metadata $metadata): void
    {
        Cache::forget('metadata.repositories.charts');
        Cache::forget('metadata.repositories.recently_adds');
        //Cache::forget('metadata.metricCards');
        Cache::forget('metadata.count');
    }

    /**
     * Handle the MetaData "deleted" event.
     */
    public function deleted(Metadata $metadata): void
    {
        Cache::forget('metadata.repositories.charts');
        Cache::forget('metadata.repositories.recently_adds');
        //Cache::forget('metadata.metricCards');
        Cache::forget('metadata.count');
    }

    /**
     * Handle the MetaData "restored" event.
     */
    public function restored(Metadata $metadata): void
    {
        Cache::forget('metadata.repositories.charts');
        Cache::forget('metadata.repositories.recently_adds');
        //Cache::forget('metadata.metricCards');
        Cache::forget('metadata.count');
    }

    /**
     * Handle the MetaData "force deleted" event.
     */
    public function forceDeleted(Metadata $metadata): void
    {
        Cache::forget('metadata.repositories.charts');
        Cache::forget('metadata.repositories.recently_adds');
        //Cache::forget('metadata.metricCards');
        Cache::forget('metadata.count');
    }
}
