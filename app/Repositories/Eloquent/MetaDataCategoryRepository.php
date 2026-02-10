<?php

namespace App\Repositories\Eloquent;

use Exception;
use Throwable;
use App\Models\MetaDataCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Services\PdfWatermarkService;
use Illuminate\Support\Facades\Storage;
use App\Data\Activity\CreateActivityData;
use App\Data\MetadataCategory\MetadataCategoryData;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Data\MetadataCategory\CreateMetadataCategoryData;
use App\Data\MetadataCategory\UpdateMetadataCategoryData;
use App\Repositories\Contratcs\ActivityRepositoryInterface;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use App\Repositories\Contratcs\MetaDataCategoryRepositoryInterface;

class MetaDataCategoryRepository implements MetaDataCategoryRepositoryInterface
{
    public function __construct(
        protected MetaDataRepositoryInterface $metaDataRepository,
        protected ActivityRepositoryInterface $activityRepository
    ) {}

    public function create(CreateMetadataCategoryData $create_metadata_category_data): MetadataCategoryData|Throwable
    {
        try {
            // 1. Ambil path file mentah dari hasil validasi
            $tempPath = $create_metadata_category_data->file_path->getRealPath();

            // 2. Buat nama file unik
            $filename = uniqid() . '.pdf';

            // 3. Tentukan path sementara untuk menyimpan file mentah
            $tempStoragePath = storage_path("app/temp/{$filename}");

            // 4. Pastikan folder temp ada
            File::ensureDirectoryExists(dirname($tempStoragePath));

            // 5. Pindahkan file mentah ke folder temp agar bisa dibaca FPDI
            File::copy($tempPath, $tempStoragePath);

            // 6. Terapkan watermark dan simpan ke storage publik
            $relativePath = PdfWatermarkService::apply(
                $tempStoragePath,
                $filename // ✅ pastikan hanya nama file, bukan path
            );

            // 7. Hapus file temp
            File::delete($tempStoragePath);

            $meta_data_category = MetaDataCategory::create([
                'meta_data_id' => $create_metadata_category_data->meta_data_id,
                'category_id' => $create_metadata_category_data->category_id,
                'file_path' => $relativePath
            ]);

            return MetadataCategoryData::fromModel($meta_data_category);
        } catch (Exception $e) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'MetaDataCategoryRepository',
                        'method' => 'create',
                    ],
                    'data' => [
                        'create_metadata_category_data' => $create_metadata_category_data,
                    ]
                ],
                'message' => $e->getMessage()
            ], JSON_PRETTY_PRINT));

            throw new Exception($e->getMessage());
        }
    }

    public function update(UpdateMetadataCategoryData $update_metadata_category_data, int $current_category_id): MetadataCategoryData|Throwable
    {
        try {
            $metadata_category_data = $this->findByMetaDataIdAndCategoryId(
                $update_metadata_category_data->meta_data_id,
                $current_category_id
            );

            $file_path = $update_metadata_category_data->file_path;

            if (!empty($file_path)) {
                // 1. Ambil path file mentah dari hasil validasi
                $tempPath = $update_metadata_category_data->file_path->getRealPath();

                // 2. Buat nama file unik
                $filename = uniqid() . '.pdf';

                // 3. Tentukan path sementara untuk menyimpan file mentah
                $tempStoragePath = storage_path("app/temp/{$filename}");

                // 4. Pastikan folder temp ada
                File::ensureDirectoryExists(dirname($tempStoragePath));

                // 5. Pindahkan file mentah ke folder temp agar bisa dibaca FPDI
                File::copy($tempPath, $tempStoragePath);

                // 6. Terapkan watermark dan simpan ke storage publik
                $relativePath = PdfWatermarkService::apply(
                    $tempStoragePath,
                    basename($filename), // ✅ pastikan hanya nama file, bukan path
                );

                // 7. Hapus file temp
                File::delete($tempStoragePath);

                if ($metadata_category_data) {
                    if (Storage::disk('public')->exists($metadata_category_data->file_path)) {
                        Storage::disk('public')->delete($metadata_category_data->file_path);
                    }
                }

                // 8. Simpan path ke database (tanpa 'public/')
                $file_path = $relativePath;
            } else {
                $file_path = $metadata_category_data->file_path;
            }

            $meta_data_category = MetaDataCategory::where('meta_data_id', $update_metadata_category_data->meta_data_id)
                ->where('category_id', $current_category_id);

            $meta_data_category->update([
                'category_id' => $update_metadata_category_data->category_id,
                'file_path' => $file_path
            ]);

            return $this->findByMetaDataIdAndCategoryId(
                $update_metadata_category_data->meta_data_id,
                $update_metadata_category_data->category_id
            );
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'MetaDataCategoryRepository',
                        'method' => 'update',
                    ],
                    'data' => [
                        'update_metadata_category_data' => $update_metadata_category_data,
                        'current_category_id' => $current_category_id,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));
            throw $th;
        }
    }

    public function findByMetaDataIdAndCategoryId(int $meta_data_id, int $category_id): MetadataCategoryData|Throwable
    {
        try {
            $meta_data_category = MetaDataCategory::where('meta_data_id', $meta_data_id)
                ->where('category_id', $category_id)->firstOrFail();

            return MetadataCategoryData::fromModel($meta_data_category);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'MetaDataCategoryRepository',
                        'method' => 'findByMetaDataIdAndCategoryId',
                    ],
                    'data' => [
                        'meta_data_id' => $meta_data_id,
                        'category_id' => $category_id,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function findByMetaDataSlugAndCategorySlug(string $meta_data_slug, string $category_slug): MetadataCategoryData|Throwable
    {
        try {
            $meta_data_category = MetaDataCategory::whereHas(
                'category',
                fn($query) => $query->where('slug', $category_slug)
            )
                ->whereHas(
                    'metadata',
                    function ($query) use ($meta_data_slug) {
                        $user = Auth::user();
                        if ($user->hasRole('author')) {
                            $query->where('author_id', $user->author->id);
                        }
                        $query->where('slug', $meta_data_slug);
                    }
                )
                ->firstOrFail();

            return MetadataCategoryData::fromModel($meta_data_category);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'MetaDataCategoryRepository',
                        'method' => 'findByMetaDataSlugAndCategorySlug',
                    ],
                    'data' => [
                        'meta_data_slug' => $meta_data_slug,
                        'category_slug' => $category_slug,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw new Exception('Sorry, repository not found.');
        }
    }

    public function delete(int $meta_data_id, int $category_id): bool|Throwable
    {
        try {
            $repository = MetaDataCategory::where('meta_data_id', $meta_data_id)
                ->where('category_id', $category_id);

            if ($repo = $repository->firstOrFail()) {
                if ($file_path = $repo->file_path) {
                    if (Storage::disk('public')->exists($file_path)) {
                        Storage::disk('public')->delete($file_path);
                    }
                }
            }

            return $repository->delete();
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'MetaDataCategoryRepository',
                        'method' => 'delete',
                    ],
                    'data' => [
                        'meta_data_id' => $meta_data_id,
                        'category_id' => $category_id,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

            throw $th;
        }
    }

    public function read(string $category_slug, string $meta_data_slug): BinaryFileResponse
    {
        $repository = MetaDataCategory::whereHas(
            'category',
            fn($query) => $query->where('slug', $category_slug)
        )->whereHas(
            'metadata',
            fn($query) => $query->where('slug', $meta_data_slug)
        )->firstOrFail();

        $request = request();

        $create_activity_data = CreateActivityData::from([
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => $request->user()?->id,
            'meta_data_id' => $repository->metadata->id,
            'category_id' => $repository->category->id
        ]);

        $this->activityRepository->create($create_activity_data);

        $path = storage_path('app/public/' . $repository->file_path);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
