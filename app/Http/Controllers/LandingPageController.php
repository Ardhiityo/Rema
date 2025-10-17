<?php

namespace App\Http\Controllers;

use App\Repositories\Contratcs\MetaDataCategoryRepositoryInterface;

class LandingPageController extends Controller
{
    public function __construct(protected MetaDataCategoryRepositoryInterface $metaDataCategoryRepository) {}

    public function index()
    {
        return view('index');
    }

    public function read($category_slug, $meta_data_slug)
    {
        return $this->metaDataCategoryRepository->read($category_slug, $meta_data_slug);
    }
}
