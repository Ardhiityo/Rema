<?php

namespace App\Repositories\Eloquent;

use Throwable;
use App\Models\MetaData;
use Illuminate\Support\Facades\DB;
use App\Data\Metadata\MetadataData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Data\MetaData\UpdateMetaData;
use Spatie\LaravelData\DataCollection;
use App\Data\Metadata\MetadataListData;
use Illuminate\Support\Facades\Storage;
use App\Data\Metadata\CreateMetadataData;
use App\Data\Metadata\MetadataReportData;
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
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'MetaDataRepository',
                        'method' => 'create',
                    ],
                    'data' => [
                        'create_meta_data' => $create_meta_data,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));
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
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'MetaDataRepository',
                        'method' => 'update',
                    ],
                    'data' => [
                        'meta_data_id' => $meta_data_id,
                        'update_meta_data' => $update_meta_data,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

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
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'MetaDataRepository',
                        'method' => 'findById',
                    ],
                    'data' => [
                        'meta_data_id' => $meta_data_id,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function findBySlug(string $meta_data_slug, array|null $relations = null): MetadataData|Throwable
    {
        $meta_data = MetaData::where('slug', $meta_data_slug)->firstOrFail();

        if ($relations) {
            $meta_data->load($relations);
        }

        return MetadataData::fromModel($meta_data);
    }

    public function findByFilters(string $keyword, string $status, string $year, string $visibility, bool $is_master_data = false): LengthAwarePaginator
    {
        $user = Auth::user();

        $query = MetaData::query()
            ->with(['author', 'author.user', 'author.studyProgram', 'categories', 'activities'])
            ->when(
                $year,
                function ($query) use ($year) {
                    $query->where('year', $year);
                }
            );

        if ($user->hasRole('contributor')) {
            if ($is_master_data) {
                $query = $query->where('visibility', '!=', 'private')
                    ->when(
                        $keyword,
                        function ($query) use ($keyword) {
                            $query
                                ->whereLike('title', "%$keyword%")
                                ->orWhereHas(
                                    'author.user',
                                    fn($query) => $query->whereLike('name', "%$keyword%")
                                );
                        }
                    );
            } else {
                $query = $query
                    ->where('author_id', $user->author->id)
                    ->where('status', $status)
                    ->where('visibility', $visibility)
                    ->when(
                        $keyword,
                        function ($query) use ($keyword) {
                            $query
                                ->whereLike('title', "%$keyword%");
                        }
                    );
            }
        } else {
            if ($is_master_data) {
                $query = $query
                    ->where('status', $status)
                    ->where('visibility', $visibility)
                    ->when(
                        $keyword,
                        function ($query) use ($keyword) {
                            $query
                                ->whereLike('title', "%$keyword%")
                                ->orWhereHas(
                                    'author.user',
                                    fn($query) => $query->whereLike('name', "%$keyword%")
                                );
                        }
                    );
            }
        }

        $meta_data = $query
            ->orderByDesc('id')
            ->paginate(10);

        return MetadataListData::collect($meta_data);
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
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'MetaDataRepository',
                        'method' => 'delete',
                    ],
                    'data' => [
                        'meta_data_id' => $meta_data_id,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

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
