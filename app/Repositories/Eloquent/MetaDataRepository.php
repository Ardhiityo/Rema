<?php

namespace App\Repositories\Eloquent;

use Throwable;
use App\Models\MetaData;
use App\Data\Metadata\MetadataData;
use Illuminate\Support\Facades\Auth;
use App\Data\MetaData\UpdateMetaData;
use App\Data\Metadata\MetadataListData;
use Illuminate\Support\Facades\Storage;
use App\Data\Metadata\CreateMetadataData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use Exception;

class MetaDataRepository implements MetaDataRepositoryInterface
{
    public function create(CreateMetadataData $create_meta_data): MetadataData
    {
        try {
            $meta_data = MetaData::create([
                'title' => ucwords(strtolower($create_meta_data->title)),
                'author_id' => $create_meta_data->author_id,
                'visibility' => $create_meta_data->visibility,
                'year' => $create_meta_data->year,
                'slug' => $create_meta_data->slug,
                'status' => $create_meta_data->status
            ]);

            return MetadataData::fromModel($meta_data);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function update($meta_data_id, UpdateMetaData $update_meta_data): MetadataData|Throwable
    {
        try {
            $meta_data = MetaData::findOrFail($meta_data_id);

            $meta_data->update([
                'title' => $update_meta_data->title,
                'author_id' => $update_meta_data->author_id,
                'visibility' => $update_meta_data->visibility,
                'year' => $update_meta_data->year,
                'slug' => $update_meta_data->slug,
                'status' => $update_meta_data->status
            ]);

            return MetadataData::fromModel($meta_data->refresh());
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function findById(int $meta_data_id, array|null $relations = null): MetadataData|Throwable
    {
        try {
            $meta_data = MetaData::findOrFail($meta_data_id);

            if ($relations) {
                $meta_data->load($relations);
            }

            return MetadataData::fromModel($meta_data);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function findBySlug(string $meta_data_slug, array|null $relations = null): MetadataData|Throwable
    {
        $meta_data = MetaData::firstWhere('slug', $meta_data_slug);

        if ($meta_data) {
            if ($relations) {
                $meta_data->load($relations);
            }
            return MetadataData::fromModel($meta_data);
        }

        throw new Exception('Meta data slug not found');
    }

    public function findByFilters(string $title, string $status, string $year, string $visibility, bool $is_author = false): LengthAwarePaginator
    {
        $query = MetaData::query();
        $user = Auth::user();

        $query->with(['author', 'author.user', 'author.studyProgram', 'categories']);

        if ($user->hasRole('contributor')) {
            if ($is_author) {
                $query->where('author_id', $user->author->id);
            } else {
                $query->where('visibility', '!=', 'private');
            }
        }

        $query->where('status', $status);

        $query->where('visibility', $visibility);

        if ($title) {
            $query->whereLike('title', "%$title%");
        }

        if ($year) {
            $query->where('year', $year);
        }

        return MetadataListData::collect(
            $query->orderByDesc('id')->paginate(10)
        );
    }

    public function delete(int $meta_data_id): bool|Throwable
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
        } catch (Throwable $th) {
            throw $th;
        }
    }
}
