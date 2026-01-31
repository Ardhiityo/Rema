<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Spatie\LaravelPdf\Enums\Format;
use function Spatie\LaravelPdf\Support\pdf;
use App\Repositories\Contratcs\AuthorRepositoryInterface;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use App\Repositories\Contratcs\CoordinatorRepositoryInterface;

class ReportController extends Controller
{
    public function activity($year)
    {
        $meta_data = app(MetaDataRepositoryInterface::class)->reports($year);

        /**
         * Need : Node js
        // return pdf()
        //     ->format(Format::A4)
        //     ->margins(top: 10, right: 20, left: 20, bottom: 20)
        //     ->view('reports.activity', compact('meta_data', 'year'))
        //     ->name("activity-report-" . $year . ".pdf");
         */

        $mpdf = new Mpdf();
        $mpdf->WriteHTML(view('reports.activity',  compact('meta_data', 'year')));
        return $mpdf->Output();
    }

    public function repository($nidn, $year, $includes)
    {
        $includes = json_decode($includes);

        $authors = app(AuthorRepositoryInterface::class)
            ->reports($year, $includes, $nidn);

        $coordinator_data = app(CoordinatorRepositoryInterface::class)->findByNidn($nidn);

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

        return pdf()
            ->format(Format::A4)
            ->margins(top: 10, right: 20, left: 20, bottom: 20)
            ->view('reports.repository', compact('year', 'authors', 'sub_title', 'coordinator_data'))
            ->name("repository-report-" . $year . ".pdf");
    }
}
