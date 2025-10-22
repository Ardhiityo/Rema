<?php

namespace App\Repositories\Eloquent;

use Throwable;
use App\Models\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Services\PdfWatermarkService;
use Illuminate\Support\Facades\Storage;
use App\Data\MetadataCategory\MetadataCategoryData;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Data\MetadataCategory\CreateMetadataCategoryData;
use App\Data\MetadataCategory\UpdateMetadataCategoryData;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use App\Repositories\Contratcs\MetaDataCategoryRepositoryInterface;

class MetaDataCategoryRepository implements MetaDataCategoryRepositoryInterface
{
    public function __construct(protected MetaDataRepositoryInterface $metaDataRepository) {}

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
                basename($filename), // ✅ pastikan hanya nama file, bukan path
            );

            // 7. Hapus file temp
            File::delete($tempStoragePath);

            $repository = Repository::create([
                'meta_data_id' => $create_metadata_category_data->meta_data_id,
                'category_id' => $create_metadata_category_data->category_id,
                'file_path' => $relativePath
            ]);

            return MetadataCategoryData::fromModel($repository);
        } catch (Throwable $th) {
            throw $th;
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

            if (!is_null($file_path)) {
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

            $repository = Repository::where('meta_data_id', $update_metadata_category_data->meta_data_id)
                ->where('category_id', $current_category_id);

            $repository->update([
                'category_id' => $update_metadata_category_data->category_id,
                'file_path' => $file_path
            ]);

            return $this->findByMetaDataIdAndCategoryId(
                $update_metadata_category_data->meta_data_id,
                $update_metadata_category_data->category_id
            );
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function findByMetaDataIdAndCategoryId(int $meta_data_id, int $category_id): MetadataCategoryData|null
    {
        try {
            $metadata_category_data = Repository::where('meta_data_id', $meta_data_id)
                ->where('category_id', $category_id)->firstOrFail();

            return MetadataCategoryData::fromModel($metadata_category_data);
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function findByMetaDataSlugAndCategorySlug(string $meta_data_slug, string $category_slug): MetadataCategoryData|null
    {
        try {
            $meta_data_category_data = Repository::whereHas(
                'category',
                fn($query) => $query->where('slug', $category_slug)
            )
                ->whereHas(
                    'metadata',
                    function ($query) use ($meta_data_slug) {
                        $user = Auth::user();
                        if ($user->hasRole('contributor')) {
                            $query->where('author_id', $user->author->id);
                        }
                        $query->where('slug', $meta_data_slug);
                    }
                )
                ->firstOrFail();

            return MetadataCategoryData::fromModel($meta_data_category_data);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function delete(int $meta_data_id, int $category_id): bool
    {
        try {
            $repository = Repository::where('meta_data_id', $meta_data_id)
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
            logger(json_encode($th->getMessage()));
            throw $th;
        }
    }

    public function read(string $category_slug, string $meta_data_slug): BinaryFileResponse
    {
        $repository = Repository::whereHas(
            'category',
            fn($query)
            => $query->where('slug', $category_slug)
        )->whereHas(
            'metadata',
            fn($query) => $query->where('slug', $meta_data_slug)
        )->firstOrFail();

        $path = storage_path('app/public/' . $repository->file_path);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
