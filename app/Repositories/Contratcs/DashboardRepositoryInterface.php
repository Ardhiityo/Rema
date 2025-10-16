<?php

namespace App\Repositories\Contratcs;

use Spatie\LaravelData\DataCollection;

interface DashboardRepositoryInterface
{
    public function metricCards(): DataCollection;
}
