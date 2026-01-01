<?php

namespace App\Repositories\Eloquent;

use Throwable;
use App\Models\Category;
use App\Data\Category\CategoryData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Facades\Storage;
use App\Data\Category\CreateCategoryData;
use App\Data\Category\UpdateCategoryData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contratcs\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function create(CreateCategoryData $category): CategoryData|Throwable
    {
        try {
            $category = Category::create([
                'name' => ucwords(strtolower($category->name)),
                'slug' => $category->slug
            ]);

            return CategoryData::fromModel($category);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'CategoryRepository',
                        'method' => 'create',
                    ],
                    'data' => [
                        'category' => $category,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function findById(int $category_id): CategoryData|Throwable
    {
        try {
            $category = Category::findOrFail($category_id);

            return CategoryData::fromModel($category);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'CategoryRepository',
                        'method' => 'findById',
                    ],
                    'data' => [
                        'category_id' => $category_id,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function update(int $category_id, UpdateCategoryData $update_category_data): CategoryData|Throwable
    {
        try {
            $category = Category::findOrFail($category_id);

            $category->update([
                'name' => ucwords(strtolower($update_category_data->name)),
                'slug' => $update_category_data->slug
            ]);

            return CategoryData::fromModel($category->refresh());
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'CategoryRepository',
                        'method' => 'update',
                    ],
                    'data' => [
                        'category_id' => $category_id,
                        'update_category_data' => $update_category_data,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function delete(int $category_id): bool|Throwable
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
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'CategoryRepository',
                        'method' => 'delete',
                    ],
                    'data' => [
                        'category_id' => $category_id,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function all(): DataCollection
    {
        return Cache::rememberForever('category.all', function () {
            return CategoryData::collect(
                Category::orderByDesc('id')->get(),
                DataCollection::class
            );
        });
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

    public function first(): CategoryData|null
    {
        try {
            $category = Category::firstOrFail();

            return CategoryData::fromModel($category);
        } catch (Throwable $th) {
            return null;
        }
    }
}
