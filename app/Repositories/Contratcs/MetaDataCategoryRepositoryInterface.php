<?php

namespace App\Repositories\Contratcs;

use App\Data\MetadataCategory\MetadataCategoryData;

interface MetaDataCategoryRepositoryInterface
{
    public function findByMetaDataSlugAndCategorySlug($meta_data_slug, $category_slug): MetadataCategoryData|null;
}
