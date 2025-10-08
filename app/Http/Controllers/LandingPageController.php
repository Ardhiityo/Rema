<?php

namespace App\Http\Controllers;

use App\Models\Repository;

class LandingPageController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function read($meta_data_id, $category_id)
    {
        $repository = Repository::where('meta_data_id', $meta_data_id)
            ->where('category_id', $category_id)->firstOrFail();

        $path = storage_path('app/public/' . $repository->file_path);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
