<?php

namespace App\Repositories\Contratcs;

use App\Data\Category\CreateCategoryData;
use App\Data\Category\UpdateCategoryData;
use App\Data\CategoryData;

interface CategoryRepositoryInterface
{
    public function create(CreateCategoryData $create_category_data);
    public function findById(int $category_id): CategoryData;
    public function update(int $category_id, UpdateCategoryData $update_category_data): CategoryData;
    public function delete(int $category_id): bool;
}
