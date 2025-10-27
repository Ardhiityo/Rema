<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;

class ActivityExport implements FromView
{
    public function __construct(public string|int $year) {}

    public function metaDataRepository()
    {
        return app(MetaDataRepositoryInterface::class);
    }

    public function view(): View
    {
        $year = $this->year;

        $meta_data = $this->metaDataRepository()
            ->reports($year);

        return view('reports.activity', compact(
            'meta_data',
            'year'
        ));
    }
}
