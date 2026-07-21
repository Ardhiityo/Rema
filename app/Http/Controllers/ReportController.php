<?php

namespace App\Http\Controllers;

use App\Repositories\Contratcs\CoordinatorRepositoryInterface;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class ReportController extends Controller
{
    public function activity($year)
    {
        $meta_data = app(MetaDataRepositoryInterface::class)
            ->activityReports($year);

        $mpdf = new Mpdf;

        $html = view(
            'reports.activity',
            compact('meta_data', 'year')
        )->render();

        $mpdf->WriteHTML($html);

        return $mpdf->Output(
            'Remafik_Activities_Report_'.now()->year.'.pdf',
            Destination::INLINE
        );
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

        $sub_title = "Author's Report With The Category Of ".implode(', ', $includes);

        $params['sub_title'] = $sub_title;

        $mpdf = new Mpdf;

        $html = view('reports.author', $params)->render();

        $mpdf->WriteHTML($html);

        return $mpdf->Output(
            'Remafik_Latest_Reports_'.now()->format('Y-m-d').'.pdf',
            Destination::INLINE
        );
    }
}
