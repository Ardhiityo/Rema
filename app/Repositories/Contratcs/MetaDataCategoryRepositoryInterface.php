<?php

namespace App\Repositories\Contratcs;

use App\Data\MetadataCategory\MetadataCategoryData;
use App\Data\MetadataCategory\CreateMetadataCategoryData;
use App\Data\MetadataCategory\UpdateMetadataCategoryData;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface MetaDataCategoryRepositoryInterface
{
    public function create(CreateMetadataCategoryData $create_metadata_category_data): MetadataCategoryData;

    public function update(UpdateMetadataCategoryData $update_metadata_category_data, int $current_category_id): MetadataCategoryData;

    public function findByMetaDataSlugAndCategorySlug(string $meta_data_slug, string $category_slug): MetadataCategoryData|null;

    public function findByMetaDataIdAndCategoryId(int $meta_data_id, int $category_id): MetadataCategoryData|null;

    public function delete(int $meta_data_id, int $category_id): bool;

    public function read(string $category_slug, string $meta_data_slug): BinaryFileResponse;
}
