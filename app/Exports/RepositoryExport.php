<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Repositories\Contratcs\AuthorRepositoryInterface;

class RepositoryExport implements FromView
{
    public function __construct(public string|int $year, public array $includes) {}

    public function authorRepository()
    {
        return app(AuthorRepositoryInterface::class);
    }

    public function view(): View
    {
        $year = $this->year;
        $includes = $this->includes;

        $authors = $this->authorRepository()->reports($year, $includes);

        $sub_title = '';

        if (empty($includes)) {
            $sub_title = "Author Reports In $year That Have Not Been Fully Completed In The Repository";
        }

        if (!empty($includes)) {

            $includes = array_map(function ($item) {
                return ucwords(str_replace('-', ' ', $item));
            }, $includes);

            $sub_title = "Author's Report In $year That Has Been Completed " . implode(', ', $includes);
        }

        return view('reports.repository', compact('year', 'authors', 'sub_title'));
    }
}
