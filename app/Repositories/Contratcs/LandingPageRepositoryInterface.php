<?php

namespace App\Repositories\Contratcs;

use Illuminate\Pagination\LengthAwarePaginator;

interface LandingPageRepositoryInterface
{
    public function searchHero(string $title, string $year, string $author, string $category): LengthAwarePaginator;
}
