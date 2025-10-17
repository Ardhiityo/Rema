<?php

namespace App\Http\Controllers;

use App\Repositories\Contratcs\DashboardRepositoryInterface;

class DashboardController extends Controller
{
    public function index(DashboardRepositoryInterface $dashboardRepository)
    {
        return view(
            'pages.dashboard',
            $dashboardRepository->charts()
        );
    }
}
