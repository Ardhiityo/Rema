<?php

namespace App\Repositories\Eloquent;

use App\Data\Metadata\CreateMetadataData;
use App\Data\Metadata\MetadataActivityReportData;
use App\Data\Metadata\MetadataAuthorReportData;
use App\Data\Metadata\MetadataData;
use App\Data\Metadata\MetadataListData;
use App\Data\MetaData\UpdateMetaData;
use App\Models\Coordinator;
use App\Models\Metadata;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\DataCollection;
use Throwable;

class MetaDataRepository implements MetaDataRepositoryInterface
{
    public function create(CreateMetadataData $create_meta_data): MetadataData
    {
        try {
            $meta_data = Metadata::create([
                'title' => $create_meta_data->title_formatted,
                'author_id' => $create_meta_data->author_id,
                'author_name' => $create_meta_data->author_name_formatted,
                'author_nim' => $create_meta_data->author_nim_formatted,
                'study_program_id' => $create_meta_data->study_program_id,
                'visibility' => $create_meta_data->visibility,
                'year' => $create_meta_data->year_formatted,
                'slug' => $create_meta_data->slug,
                'status' => $create_meta_data->status,
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
                    ],
                ],
                'message' => $th->getMessage(),
            ], JSON_PRETTY_PRINT));
            throw $th;
        }
    }

    public function update($meta_data_id, UpdateMetaData $update_meta_data): MetadataData|Throwable
    {
        try {
            $meta_data = Metadata::findOrFail($meta_data_id);

            $data = [
                'title' => $update_meta_data->title_formatted,
                'author_nim' => $update_meta_data->author_nim,
                'author_name' => $update_meta_data->author_name_formatted,
                'study_program_id' => $update_meta_data->study_program_id,
                'year' => $update_meta_data->year,
                'slug' => $update_meta_data->slug,
                'visibility' => $update_meta_data->visibility,
                'status' => $update_meta_data->status,
            ];

            if (Auth::user()->hasRole('author')) {
                unset($data['status']);
                unset($data['visibility']);
            }

            $meta_data->update($data);

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
                    ],
                ],
                'message' => $th->getMessage(),
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function findById(int $meta_data_id, ?array $relations = null): MetadataData|Throwable
    {
        try {
            $meta_data = Metadata::findOrFail($meta_data_id);

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
                    ],
                ],
                'message' => $th->getMessage(),
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function findBySlug(string $meta_data_slug, ?array $relations = null): MetadataData|Throwable
    {
        $meta_data = Metadata::where('slug', $meta_data_slug)->firstOrFail();

        if ($relations) {
            $meta_data->load($relations);
        }

        return MetadataData::fromModel($meta_data);
    }

    public function findByFilters(string $keyword, string $status, string $year, string $visibility, bool $is_master_data = false): LengthAwarePaginator
    {
        $user = Auth::user();

        $query = Metadata::query()
            ->with(['categories', 'activities', 'keywords'])
            ->when(
                $year,
                function ($query) use ($year) {
                    $query->where('year', $year);
                }
            );

        if ($user->hasRole('author')) {
            if ($is_master_data) {
                // all repositories for author
                $query = $query->where('visibility', '!=', 'private')
                    ->when(
                        $keyword,
                        function ($query) use ($keyword) {
                            $query
                                ->whereLike('title', "$keyword%")
                                ->orWhereLike('author_name', "$keyword%")
                                ->orWhereHas('keywords',
                                    fn ($query) => $query->whereLike('name', "$keyword%")
                                );
                        }
                    );
            } else {
                // my data repositories for author
                $query = $query
                    ->where('author_id', $user->author->id)
                    ->where('status', $status)
                    ->where('visibility', $visibility)
                    ->when(
                        $keyword,
                        function ($query) use ($keyword) {
                            $query
                                ->whereLike('title', "$keyword%")
                                ->orWhereHas('keywords',
                                    fn ($query) => $query->whereLike('name', "$keyword%")
                                );
                        }
                    );
            }
        } else {
            if ($is_master_data) {
                if ($user->hasRole('admin')) {
                    // all repositories for admin
                    $query = $query
                        ->where('status', $status)
                        ->where('visibility', $visibility)
                        ->when(
                            $keyword,
                            function ($query) use ($keyword) {
                                $query
                                    ->whereLike('title', "$keyword%")
                                    ->orWhereLike('author_name', "$keyword%")
                                    ->orWhereHas('keywords',
                                        fn ($query) => $query->whereLike('name', "$keyword%")
                                    );
                            }
                        );
                } elseif ($user->hasRole('staff')) {
                    // all repositories for staff
                    $faculty = $user->staff->faculty;
                    $study_programs = $faculty->studyPrograms->pluck('id')->toArray();

                    $query = $query
                        ->whereIn('study_program_id', $study_programs)
                        ->where('status', $status)
                        ->where('visibility', $visibility)
                        ->when(
                            $keyword,
                            function ($query) use ($keyword) {
                                $query
                                    ->whereLike('title', "$keyword%")
                                    ->orWhereLike('author_name', "$keyword%")
                                    ->orWhereHas('keywords',
                                        fn ($query) => $query->whereLike('name', "$keyword%")
                                    );
                            }
                        );
                }
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
            $meta_data = Metadata::findOrFail($meta_data_id);

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
                    ],
                ],
                'message' => $th->getMessage(),
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function activityReports(int|string $year): DataCollection
    {
        $meta_data = Metadata::with([
            'studyProgram',
            'activities' => function ($query) {
                $query->with(['category:id,name'])
                    ->select('meta_data_id', 'category_id', DB::raw('COUNT(*) AS total'))
                    ->groupBy('meta_data_id', 'category_id');
            },
        ])
            ->where('year', $year)
            ->where('status', 'approve')
            ->select('id', 'title', 'year', 'author_name', 'author_nim', 'study_program_id')
            ->get();

        return MetadataActivityReportData::collect($meta_data, DataCollection::class);
    }

    public function authorReports(int|string $year, array $includes, int $nidn): DataCollection
    {
        $coordinator = Coordinator::where('nidn', $nidn)->first();

        $meta_data = Metadata::with(['studyProgram', 'categories'])
            ->where('year', $year)
            ->where('study_program_id', $coordinator->study_program_id)
            ->when(
                ! empty($includes),
                fn ($query) => $query->whereHas(
                    'categories',
                    fn ($query) => $query->whereIn('slug', $includes)
                )
            )
            ->when(
                empty($includes),
                fn ($query) => $query->whereDoesntHave('categories')
            )
            ->orderBy('author_nim', 'asc')
            ->get();

        return MetadataAuthorReportData::collect($meta_data, DataCollection::class);
    }

    public function metaDataCount(): int
    {
        return Cache::rememberForever('metadata.count', function () {
            return Metadata::where('status', 'approve')->count();
        });
    }
}
