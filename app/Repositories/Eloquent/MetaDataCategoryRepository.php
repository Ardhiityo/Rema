<?php

namespace App\Repositories\Eloquent;

use App\Models\Repository;
use Illuminate\Support\Facades\Auth;
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
}
