<?php

namespace App\Livewire;

use Livewire\Component;
use App\Repositories\Contratcs\DashboardRepositoryInterface;
use App\Repositories\Contratcs\AuthorRepositoryInterface;

class MetricCard extends Component
{
    public function getAuthorsCountProperty(AuthorRepositoryInterface $authorRepository)
    {
        return $authorRepository->authorCount();
    }

    public function getMetricsProperty(DashboardRepositoryInterface $dashboardRepository)
    {
        return $dashboardRepository->metricCards();
    }

    public function render()
    {
        return view('livewire.metric-card');
    }
}
