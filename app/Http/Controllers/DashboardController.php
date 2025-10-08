<?php

namespace App\Http\Controllers;

use App\Data\UserData;
use App\Models\Repository;
use App\Data\RecentlyAddData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // $repositories = Repository::where('status', 'approve')->select('year', DB::raw('count(*) as total_repositories'))
        //     ->groupBy('year')
        //     ->orderBy('year', 'asc')
        //     ->get();

        $repository_years = [];
        $repository_totals = [];

        // foreach ($repositories as $key => $repository) {
        //     $repository_years[] = $repository->year;
        //     $repository_totals[] = $repository->total_repositories;
        // }

        // $recently_adds = RecentlyAddData::collect(Repository::with(['author', 'author.user'])
        //     ->where('status', 'approve')
        //     ->limit(3)
        //     ->orderByDesc('id')
        //     ->get());
        $recently_adds = collect([]);

        $user_logged = UserData::fromModel(Auth::user());

        // $latest_repositories = RecentlyAddData::collect(
        //     Repository::with('author')
        //         ->where('status', 'approve')
        //         ->limit(5)
        //         ->orderByDesc('id')
        //         ->get()
        // );

        $latest_repositories = [];

        return view('pages.dashboard', compact(
            'repository_years',
            'repository_totals',
            'recently_adds',
            'user_logged',
            'latest_repositories'
        ));
    }
}
