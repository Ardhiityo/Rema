<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Data\CategoryData;
use App\Data\Category\CreateCategoryData;
use App\Data\Category\UpdateCategoryData;
use App\Repositories\Contratcs\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function create(CreateCategoryData $category)
    {
        Category::create([
            'name' => $category->name,
            'slug' => $category->slug
        ]);
    }

    public function findById(int $category_id): CategoryData
    {
        $category = Category::findOrFail($category_id);

        return CategoryData::fromModel($category);
    }

    public function update(int $category_id, UpdateCategoryData $update_category_data): CategoryData
    {
        $category = Category::findOrFail($category_id);

        $category->update([
            'name' => $update_category_data->name,
            'slug' => $update_category_data->slug
        ]);

        return CategoryData::fromModel($category->refresh());
    }

    public function delete(int $category_id): bool
    {
        $category = Category::findOrFail($category_id);

        return $category->delete();
    }
}
