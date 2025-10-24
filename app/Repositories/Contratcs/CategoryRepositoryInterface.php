<?php

namespace App\Repositories\Contratcs;

use App\Data\Category\CategoryData;
use Spatie\LaravelData\DataCollection;
use App\Data\Category\CreateCategoryData;
use App\Data\Category\UpdateCategoryData;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

interface CategoryRepositoryInterface
{
    public function create(CreateCategoryData $create_category_data): CategoryData|Throwable;

    public function findById(int $category_id): CategoryData|Throwable;

    public function update(int $category_id, UpdateCategoryData $update_category_data): CategoryData|Throwable;

    public function delete(int $category_id): bool|Throwable;

    public function all(): DataCollection;

    public function findByFilters(string|null $keyword = null): LengthAwarePaginator;

    public function first(): CategoryData|null;
}
