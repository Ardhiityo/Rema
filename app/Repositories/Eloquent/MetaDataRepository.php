<?php

namespace App\Repositories\Eloquent;

use App\Models\MetaData;
use App\Data\Metadata\MetadataData;
use App\Data\Metadata\CreateMetaData;
use App\Data\MetaData\UpdateMetaData;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;

class MetaDataRepository implements MetaDataRepositoryInterface
{
    public function create(CreateMetaData $create_meta_data): MetadataData
    {
        $meta_data = MetaData::create([
            'title' => $create_meta_data->title,
            'abstract' => $create_meta_data->abstract,
            'author_id' => $create_meta_data->author_id,
            'visibility' => $create_meta_data->visibility,
            'year' => $create_meta_data->year,
            'slug' => $create_meta_data->slug,
            'status' => $create_meta_data->status
        ]);

        return MetadataData::fromModel($meta_data);
    }

    public function update($meta_data_id, UpdateMetaData $update_meta_data): MetadataData|null
    {
        try {
            $meta_data = MetaData::findOrFail($meta_data_id);

            $meta_data->update([
                'title' => $update_meta_data->title,
                'abstract' => $update_meta_data->abstract,
                'author_id' => $update_meta_data->author_id,
                'visibility' => $update_meta_data->visibility,
                'slug' => $update_meta_data->slug,
                'status' => $update_meta_data->status
            ]);

            return MetadataData::fromModel($meta_data->refresh());
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function findById(int $meta_data_id, array|null $relations = null): MetadataData|null
    {
        try {
            $meta_data = MetaData::findOrFail($meta_data_id);

            if ($relations) {
                $meta_data->load($relations);
            }

            return MetadataData::fromModel($meta_data);
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function findBySlug(string $meta_data_slug, array|null $relations = null): MetadataData|null
    {
        $meta_data = MetaData::firstWhere('slug', $meta_data_slug);

        if ($meta_data) {
            if ($relations) {
                $meta_data->load($relations);
            }
            return MetadataData::fromModel($meta_data);
        }

        return null;
    }
}
