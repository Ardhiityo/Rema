<?php

namespace App\Repositories\Contratcs;

use App\Data\Category\CategoryData;
use Spatie\LaravelData\DataCollection;
use App\Data\Category\CreateCategoryData;
use App\Data\Category\UpdateCategoryData;

interface CategoryRepositoryInterface
{
    public function create(CreateCategoryData $create_category_data): CategoryData;

    public function findById(int $category_id): CategoryData;

    public function update(int $category_id, UpdateCategoryData $update_category_data): CategoryData;

    public function delete(int $category_id): bool;

    public function all(): DataCollection;
}
