<?php

namespace App\Http\Controllers;

use App\Models\Repository;

class LandingPageController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function read(Repository $repository)
    {
        $path = storage_path('app/public/' . $repository->file_path);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
