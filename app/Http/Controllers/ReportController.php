<?php

namespace App\Http\Controllers;

use App\Repositories\Contratcs\CoordinatorRepositoryInterface;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    public function activity($year)
    {
        $meta_data = app(MetaDataRepositoryInterface::class)
            ->activityReports($year);

        $mpdf = new Mpdf;

        $mpdf->WriteHTML(view(
            'reports.activity',
            compact('meta_data', 'year')
        ));

        return $mpdf->Output();
    }

    public function repository($nidn, $year, $status, $includes)
    {
        $includes = json_decode($includes);

        $nidn = $nidn === 'empty' ? false : $nidn;

        $meta_data = app(MetaDataRepositoryInterface::class)
            ->authorReports($year, $includes, $nidn, $status);

        $params = [];
        $params['year'] = $year;
        $params['meta_data'] = $meta_data;

        if ($nidn) {
            $params['coordinator_data'] = app(CoordinatorRepositoryInterface::class)
                ->findByNidn($nidn);
        }

        $includes = array_map(function ($item) {
            return ucwords(str_replace('-', ' ', $item));
        }, $includes);

        $sub_title = "Author's Report In $year With The Category Of ".implode(', ', $includes);

        $params['sub_title'] = $sub_title;

        $mpdf = new Mpdf;

        $mpdf->WriteHTML(view('reports.author', $params));

        return $mpdf->Output();
    }
}
