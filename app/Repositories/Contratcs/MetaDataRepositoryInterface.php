<?php

namespace App\Repositories\Contratcs;

use App\Data\Metadata\MetadataData;
use App\Data\MetaData\UpdateMetaData;
use App\Data\Metadata\CreateMetadataData;
use Illuminate\Pagination\LengthAwarePaginator;

interface MetaDataRepositoryInterface
{
    public function create(CreateMetadataData $create_meta_data): MetadataData;

    public function findById(int $meta_data_id, array|null $relations = null): MetadataData|null;

    public function findBySlug(string $meta_data_slug, array|null $relations = null): MetadataData|null;

    public function update(int $meta_data_id, UpdateMetaData $update_meta_data): MetadataData|null;

    public function findByFilters(string $title, string $status, string $year, string $visibility, bool $is_author = false): LengthAwarePaginator;

    public function delete(int $meta_data_id): bool;
}
