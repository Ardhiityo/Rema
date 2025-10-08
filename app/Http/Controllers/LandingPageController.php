<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function read($category_slug, $meta_data_slug,)
    {
        $repository = Repository::whereHas(
            'category',
            fn($query)
            => $query->where('slug', $category_slug)
        )->whereHas(
            'metadata',
            fn($query) => $query->where('slug', $meta_data_slug)
        )->firstOrFail();

        $path = storage_path('app/public/' . $repository->file_path);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
