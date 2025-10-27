<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Data\Coordinator\CoordinatorData;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;

class ActivityExport implements FromView
{
    public function __construct(public string|int $year, public CoordinatorData $coordinator_data) {}

    public function metaDataRepository()
    {
        return app(MetaDataRepositoryInterface::class);
    }

    public function view(): View
    {
        $year = $this->year;

        $meta_data = $this->metaDataRepository()
            ->reports($year);

        $coordinator_data = $this->coordinator_data;

        return view('reports.activity', compact(
            'meta_data',
            'year',
            'coordinator_data'
        ));
    }
}
