<?php

namespace App\Repositories\Contratcs;

use App\Data\Metadata\MetadataData;
use App\Data\MetaData\CreateMetaData;
use App\Data\MetaData\UpdateMetaData;

interface MetaDataRepositoryInterface
{
    public function create(CreateMetaData $create_meta_data): MetadataData;
    public function findById(int $meta_data_id, array|null $relations = null): MetadataData|null;
    public function findBySlug(string $meta_data_slug, array|null $relations = null): MetadataData|null;
    public function update(int $meta_data_id, UpdateMetaData $update_meta_data): MetadataData|null;
}
