<?php

namespace App\Repositories\Eloquent;

use App\Models\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Data\MetadataCategory\MetadataCategoryData;
use App\Repositories\Contratcs\MetaDataCategoryRepositoryInterface;

class MetaDataCategoryRepository implements MetaDataCategoryRepositoryInterface
{
    public function findByMetaDataSlugAndCategorySlug($meta_data_slug, $category_slug): MetadataCategoryData|null
    {
        try {
            $meta_data_category_data = Repository::whereHas(
                'category',
                fn($query) => $query->where('slug', $category_slug)
            )
                ->whereHas(
                    'metadata',
                    function ($query) use ($meta_data_slug) {
                        $user = Auth::user();
                        if ($user->hasRole('contributor')) {
                            $query->where('author_id', $user->author->id);
                        }
                        $query->where('slug', $meta_data_slug);
                    }
                )
                ->firstOrFail();

            return MetadataCategoryData::fromModel($meta_data_category_data);
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function delete(int $meta_data_id, int $category_id): bool
    {
        $repository = Repository::where('meta_data_id', $meta_data_id)
            ->where('category_id', $category_id);

        if ($file_path = $repository->first()->file_path) {
            if (Storage::disk('public')->exists($file_path)) {
                Storage::disk('public')->delete($file_path);
            }
        }

        return $repository->delete();
    }
}
