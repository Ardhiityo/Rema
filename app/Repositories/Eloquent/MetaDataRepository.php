<?php

namespace App\Repositories\Eloquent;

use Exception;
use Throwable;
use App\Models\MetaData;
use Illuminate\Support\Facades\DB;
use App\Data\Metadata\MetadataData;
use Illuminate\Support\Facades\Auth;
use App\Data\MetaData\UpdateMetaData;
use Spatie\LaravelData\DataCollection;
use App\Data\Metadata\MetadataListData;
use Illuminate\Support\Facades\Storage;
use App\Data\Metadata\CreateMetadataData;
use App\Data\MetaData\MetadataReportData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;

class MetaDataRepository implements MetaDataRepositoryInterface
{
    public function create(CreateMetadataData $create_meta_data): MetadataData
    {
        try {
            $meta_data = MetaData::create([
                'title' => $create_meta_data->title_formatted,
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
                'title' => $update_meta_data->title_formatted,
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
            logger(json_encode($th->getMessage()));
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

    public function findByFilters(string $keyword, string $status, string $year, string $visibility, bool $is_author = false): LengthAwarePaginator
    {
        $query = MetaData::query();
        $user = Auth::user();

        $query->with(['author', 'author.user', 'author.studyProgram', 'categories', 'activities']);

        if ($user->hasRole('contributor')) {
            if ($is_author) {
                $query->where('author_id', $user->author->id);
            } else {
                $query->where('visibility', '!=', 'private');
            }
        }

        $query->where('status', $status);

        $query->where('visibility', $visibility);

        if ($keyword) {
            $query->whereLike('title', "%$keyword%")
                ->orWhereHas(
                    'author.user',
                    fn($query) => $query->whereLike('name', "%$keyword%")
                );
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

    public function reports(int|string $year): DataCollection
    {
        $meta_data = MetaData::with([
            'author:id,user_id,nim,study_program_id',
            'author.studyProgram:id,name',
            'author.user:id,name',
            'activities' => function ($query) {
                $query->with(['category:id,name'])
                    ->select('meta_data_id', 'category_id', DB::raw('COUNT(*) AS total'))
                    ->groupBy('meta_data_id', 'category_id');
            }
        ])
            ->where('year', $year)
            ->where('status', 'approve')
            ->select('id', 'title', 'author_id', 'year')
            ->get();

        return MetadataReportData::collect($meta_data, DataCollection::class);
    }
}
