<?php

namespace App\Observers;

use App\Models\MetaData;
use Illuminate\Support\Facades\Cache;

class MetaDataObserver
{
    /**
     * Handle the MetaData "created" event.
     */
    public function created(MetaData $metaData): void
    {
        Cache::forget('metadata.repositories.charts');
        Cache::forget('metadata.repositories.recently_adds');
        Cache::forget('metadata.repositories.latest_repositories');
        Cache::forget('metadata.metricCards');
    }

    /**
     * Handle the MetaData "updated" event.
     */
    public function updated(MetaData $metaData): void
    {
        Cache::forget('metadata.repositories.charts');
        Cache::forget('metadata.repositories.recently_adds');
        Cache::forget('metadata.repositories.latest_repositories');
        Cache::forget('metadata.metricCards');
    }

    /**
     * Handle the MetaData "deleted" event.
     */
    public function deleted(MetaData $metaData): void
    {
        Cache::forget('metadata.repositories.charts');
        Cache::forget('metadata.repositories.recently_adds');
        Cache::forget('metadata.repositories.latest_repositories');
        Cache::forget('metadata.metricCards');
    }

    /**
     * Handle the MetaData "restored" event.
     */
    public function restored(MetaData $metaData): void
    {
        Cache::forget('metadata.repositories.charts');
        Cache::forget('metadata.repositories.recently_adds');
        Cache::forget('metadata.repositories.latest_repositories');
        Cache::forget('metadata.metricCards');
    }

    /**
     * Handle the MetaData "force deleted" event.
     */
    public function forceDeleted(MetaData $metaData): void
    {
        Cache::forget('metadata.repositories.charts');
        Cache::forget('metadata.repositories.recently_adds');
        Cache::forget('metadata.repositories.latest_repositories');
        Cache::forget('metadata.metricCards');
    }
}
