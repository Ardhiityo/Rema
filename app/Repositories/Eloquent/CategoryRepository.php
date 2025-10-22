<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Data\Category\CategoryData;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Facades\Storage;
use App\Data\Category\CreateCategoryData;
use App\Data\Category\UpdateCategoryData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contratcs\CategoryRepositoryInterface;
use Throwable;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function create(CreateCategoryData $category): CategoryData
    {
        try {
            $category = Category::create([
                'name' => ucwords(strtolower($category->name)),
                'slug' => $category->slug
            ]);

            return CategoryData::fromModel($category);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function findById(int $category_id): CategoryData|null
    {
        try {
            $category = Category::findOrFail($category_id);

            return CategoryData::fromModel($category);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function update(int $category_id, UpdateCategoryData $update_category_data): CategoryData|Throwable
    {
        try {
            $category = Category::findOrFail($category_id);

            $category->update([
                'name' => $update_category_data->name,
                'slug' => $update_category_data->slug
            ]);

            return CategoryData::fromModel($category->refresh());
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function delete(int $category_id): bool
    {
        try {
            $category = Category::findOrFail($category_id)->load('metadata');

            if ($category->metadata->isNotEmpty()) {
                foreach ($category->metadata as $data) {
                    $file_path = $data->pivot->file_path ?? null;
                    if ($file_path) {
                        if (Storage::disk('public')->exists($file_path)) {
                            Storage::disk('public')->delete($file_path);
                        }
                    }
                }
            }

            return $category->delete();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function all(): DataCollection
    {
        return CategoryData::collect(Category::all(), DataCollection::class);
    }

    public function findByFilters(string|null $keyword = null): LengthAwarePaginator
    {
        $query = Category::query();

        if ($keyword) {
            $query->whereLike('name', "%$keyword%");
        }

        return CategoryData::collect(
            $query->orderByDesc('id')->paginate(10)
        );
    }
}
