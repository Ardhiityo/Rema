<?php

namespace App\Repositories\Eloquent;

use App\Models\MetaData;
use App\Data\Metadata\MetadataData;
use Illuminate\Support\Facades\Auth;
use App\Data\MetaData\UpdateMetaData;
use Illuminate\Support\Facades\Route;
use App\Data\Metadata\MetadataListData;
use Illuminate\Support\Facades\Storage;
use App\Data\Metadata\CreateMetadataData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;

class MetaDataRepository implements MetaDataRepositoryInterface
{
    public function create(CreateMetadataData $create_meta_data): MetadataData
    {
        $meta_data = MetaData::create([
            'title' => $create_meta_data->title,
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

    public function findByFilters(string $title, string $status, string $year, string $visibility): LengthAwarePaginator
    {
        $query = MetaData::query();

        $user = Auth::user();

        $query->with(['author', 'author.user', 'author.studyProgram', 'categories']);

        if ($user->hasRole('contributor')) {
            if (Route::is('repository.author.index')) {
                $query->where('author_id', $user->author->id);
            } else {
                $query->where('visibility', '!=', 'private');
            }
        }

        $query->where('status', $status);

        if ($title) {
            $query->whereLike('title', "%$title%");
        }

        if ($year) {
            $query->where('year', $year);
        }

        if ($visibility) {
            $query->where('visibility', $visibility);
        }

        return MetadataListData::collect(
            $query->orderByDesc('id')->paginate(10)
        );
    }

    public function delete(int $meta_data_id): bool
    {
        try {
            $meta_data = MetaData::findOrFail($meta_data_id);

            if ($meta_data->categories->isNotEmpty()) {
                foreach ($meta_data->categories as $category) {
                    if ($file_path = $category->pivot->file_path) {
                        if (Storage::disk('public')->exists($file_path)) {
                            Storage::disk('public')->delete($file_path);
                        }
                    }
                }
            }

            return $meta_data->delete();
        } catch (\Throwable $th) {
            return false;
        }
    }
}
