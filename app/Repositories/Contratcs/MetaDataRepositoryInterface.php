<?php

namespace App\Repositories\Contratcs;

use App\Data\Metadata\MetadataData;
use App\Data\MetaData\UpdateMetaData;
use App\Data\Metadata\CreateMetadataData;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\DataCollection;
use Throwable;

interface MetaDataRepositoryInterface
{
    public function create(CreateMetadataData $create_meta_data): MetadataData|Throwable;

    public function findById(int $meta_data_id, array|null $relations = null): MetadataData|Throwable;

    public function findBySlug(string $meta_data_slug, array|null $relations = null): MetadataData|Throwable;

    public function update(int $meta_data_id, UpdateMetaData $update_meta_data): MetadataData|Throwable;

    public function findByFilters(string $title, string $status, string $year, string $visibility, bool $is_author = false): LengthAwarePaginator;

    public function delete(int $meta_data_id): bool|Throwable;

    public function reports(string|int $year): DataCollection;
}
