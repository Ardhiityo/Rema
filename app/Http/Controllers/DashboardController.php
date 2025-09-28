<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $repositories = Repository::select('year', DB::raw('count(*) as total_repositories'))
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        $repository_years = [];
        $repository_totals = [];

        foreach ($repositories as $key => $repository) {
            $repository_years[] = $repository->year;
            $repository_totals[] = $repository->total_repositories;
        }

        return view('pages.dashboard', compact('repository_years', 'repository_totals'));
    }
}
