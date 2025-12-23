<?php

namespace App\Exports;

use App\Data\Coordinator\CoordinatorData;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Repositories\Contratcs\AuthorRepositoryInterface;

class RepositoryExport implements FromView
{
    public function __construct(
        public string|int $year,
        public array $includes,
        public CoordinatorData $coordinator_data
    ) {}

    public function authorRepository()
    {
        return app(AuthorRepositoryInterface::class);
    }

    public function view(): View
    {
        $year = $this->year;
        $includes = $this->includes;
        $coordinator_data = $this->coordinator_data;

        $authors = $this->authorRepository()->reports($year, $includes, $coordinator_data->id);

        $sub_title = '';

        if (empty($includes)) {
            $sub_title = "Author Reports In $year That Have Not Been Fully Completed In The Repository";
        }

        if (!empty($includes)) {

            $includes = array_map(function ($item) {
                return ucwords(str_replace('-', ' ', $item));
            }, $includes);

            $sub_title = "Author's Report In $year With The Category Of " . implode(', ', $includes);
        }

        return view(
            'reports.repository',
            compact('year', 'authors', 'sub_title', 'coordinator_data')
        );
    }
}
