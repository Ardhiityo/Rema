<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use App\Repositories\Contratcs\CoordinatorRepositoryInterface;

class ReportController extends Controller
{
    public function activity($year)
    {
        $meta_data = app(MetaDataRepositoryInterface::class)
            ->activityReports($year);

        $mpdf = new Mpdf();

        $mpdf->WriteHTML(view(
            'reports.activity',
            compact('meta_data', 'year')
        ));

        return $mpdf->Output();
    }

    public function repository($nidn, $year, $includes)
    {
        $includes = json_decode($includes);

        $meta_data = app(MetaDataRepositoryInterface::class)
            ->authorReports($year, $includes, $nidn);

        $coordinator_data = app(CoordinatorRepositoryInterface::class)
            ->findByNidn($nidn);

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

        $mpdf = new Mpdf();

        $mpdf->WriteHTML(view(
            'reports.author',
            compact('year', 'meta_data', 'sub_title', 'coordinator_data')
        ));

        return $mpdf->Output();
    }
}
