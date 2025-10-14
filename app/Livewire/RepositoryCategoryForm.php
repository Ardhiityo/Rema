<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\MetaData;
use App\Models\Repository;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use App\Data\Category\CategoryData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Services\PdfWatermarkService;
use Illuminate\Support\Facades\Storage;
use App\Rules\RepositoryCategoryCreateRule;
use App\Rules\RepositoryCategoryUpdateRule;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use App\Repositories\Contratcs\MetaDataCategoryRepositoryInterface;

class RepositoryCategoryForm extends Component
{
    use WithFileUploads;

    // Start Form
    public int|null $meta_data_id = null;
    public int|string $category_id = '';
    public $file_path = null;
    // End Form

    public string|null $file_path_update = null;
    public int|null $category_id_update = null;
    public int|null $category_id_delete = null;
    public int|string $repository_id = '';
    public bool $is_update = false;

    public function mount()
    {
        if ($this->metaDataSession) {
            $this->meta_data_id = $this->metaDataSession->id;
        }
    }

    #[Computed()]
    public function title()
    {
        return $this->is_update ? 'Edit Repository' : 'Create Repository';
    }

    protected function rulesCreate(): array
    {
        return [
            'file_path' => ['required', 'file', 'mimes:pdf', 'max:7000'],
            'category_id' => [
                'required',
                'exists:categories,id',
                new RepositoryCategoryCreateRule($this->meta_data_id)
            ],
            'meta_data_id' => ['required', 'exists:meta_data,id']
        ];
    }

    protected function rulesUpdate(): array
    {
        return [
            'file_path' => ['nullable', 'file', 'mimes:pdf', 'max:7000'],
            'category_id' => [
                'required',
                'exists:categories,id',
                new RepositoryCategoryUpdateRule($this->meta_data_id)
            ],
            'meta_data_id' => ['required', 'exists:meta_data,id']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'file_path' => 'file',
            'author_id' => 'author',
            'category_id' => 'category'
        ];
    }

    #[Computed()]
    public function metaDataRepository()
    {
        return app(MetaDataRepositoryInterface::class);
    }

    #[Computed()]
    public function metaDataCategoryRepository()
    {
        return app(MetaDataCategoryRepositoryInterface::class);
    }

    #[Computed()]
    public function metaDataSession()
    {
        if (session()->has('meta_data')) {
            $meta_data_session = session()->get('meta_data');
            if ($this->metaDataRepository->findById($meta_data_session->id)) {
                return $meta_data_session;
            }
        }
        return false;
    }

    public function createRepository()
    {
        $validated = $this->validate($this->rulesCreate());

        // 1. Ambil path file mentah dari hasil validasi
        $tempPath = $validated['file_path']->getRealPath();

        // 2. Buat nama file unik
        $filename = uniqid() . '.pdf';

        // 3. Ambil NIM dari metadata
        $authorNim = MetaData::find($this->meta_data_id)?->author?->nim ?? 'UNKNOWN';

        // 4. Tentukan path sementara untuk menyimpan file mentah
        $tempStoragePath = storage_path("app/temp/{$filename}");

        // 5. Pastikan folder temp ada
        File::ensureDirectoryExists(dirname($tempStoragePath));

        // 6. Pindahkan file mentah ke folder temp agar bisa dibaca FPDI
        File::copy($tempPath, $tempStoragePath);

        // 7. Terapkan watermark dan simpan ke storage publik
        $relativePath = PdfWatermarkService::apply(
            $tempStoragePath,
            basename($filename), // ✅ pastikan hanya nama file, bukan path
            "FIK-UNIVAL-{$authorNim}"
        );

        // 8. Hapus file temp
        File::delete($tempStoragePath);

        // 9. Simpan path ke database (tanpa 'public/')
        $validated['file_path'] = $relativePath;

        Repository::create($validated);

        $this->resetInput();

        session()->flash('repository-success', 'The repository was successfully created.');

        $this->dispatch('refresh-repository-table');
    }

    #[On('edit-repository-category')]
    public function editRepository($meta_data_slug, $category_slug)
    {
        $meta_data_category_data = $this->metaDataCategoryRepository
            ->findByMetaDataSlugAndCategorySlug($meta_data_slug, $category_slug);

        $this->meta_data_id = $meta_data_category_data->meta_data_id;
        $this->category_id = $meta_data_category_data->category_id;

        $this->category_id_update = $meta_data_category_data->category_id;
        $this->file_path_update = $meta_data_category_data->file_path;

        $this->is_update = true;
    }

    public function updateRepository()
    {
        $validated = $this->validate($this->rulesUpdate());

        $exists = Repository::where('meta_data_id', $this->meta_data_id)
            ->where('category_id', '!=', $this->category_id_update)
            ->where('category_id', $validated['category_id'])
            ->exists();

        if ($exists) {
            $this->addError('category_id', 'category already exists');
            return;
        }

        if (!is_null($validated['file_path'])) {
            if ($this->file_path_update) {
                if (Storage::disk('public')->exists($this->file_path_update)) {
                    Storage::disk('public')->delete($this->file_path_update);
                }
            }
            // 1. Ambil path file mentah dari hasil validasi
            $tempPath = $validated['file_path']->getRealPath();

            // 2. Buat nama file unik
            $filename = uniqid() . '.pdf';

            // 3. Ambil NIM dari metadata
            $authorNim = MetaData::find($this->meta_data_id)?->author?->nim ?? 'UNKNOWN';

            // 4. Tentukan path sementara untuk menyimpan file mentah
            $tempStoragePath = storage_path("app/temp/{$filename}");

            // 5. Pastikan folder temp ada
            File::ensureDirectoryExists(dirname($tempStoragePath));

            // 6. Pindahkan file mentah ke folder temp agar bisa dibaca FPDI
            File::copy($tempPath, $tempStoragePath);

            // 7. Terapkan watermark dan simpan ke storage publik
            $relativePath = PdfWatermarkService::apply(
                $tempStoragePath,
                basename($filename), // ✅ pastikan hanya nama file, bukan path
                "FIK-UNIVAL-{$authorNim}"
            );

            // 8. Hapus file temp
            File::delete($tempStoragePath);

            // 9. Simpan path ke database (tanpa 'public/')
            $validated['file_path'] = $relativePath;
        } else {
            $validated['file_path'] = $this->file_path_update;
        }

        Repository::where('meta_data_id', $this->meta_data_id)
            ->where('category_id', $this->category_id_update)
            ->update([
                'category_id' => $validated['category_id'],
                'file_path' => $validated['file_path']
            ]);

        $this->resetInput();

        $this->is_update = false;

        $this->dispatch('refresh-repository-table');

        session()->flash('repository-success', 'The repository was successfully updated.');
    }

    #[On('delete-confirm-repository-category')]
    public function deleteConfirm($meta_data_slug, $category_slug)
    {
        $repository = Repository::whereHas(
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
            ->first();

        $this->meta_data_id = $repository->meta_data_id;
        $this->category_id_delete = $repository->category_id;
    }

    #[On('delete-repository-category')]
    public function delete()
    {
        $repository = Repository::where('meta_data_id', $this->meta_data_id)
            ->where('category_id', $this->category_id_delete);

        if ($file_path = $repository->first()->file_path) {
            if (Storage::disk('public')->exists($file_path)) {
                Storage::disk('public')->delete($file_path);
            }
        }

        $repository->delete();

        if (MetaData::find($this->meta_data_id)->categories->isEmpty()) {
            $this->resetInput();
        }

        $this->dispatch('refresh-repository-table');

        session()->flash('repository-success', 'The repository was successfully deleted.');
    }

    public function resetInput()
    {
        $this->file_path = null;
        $this->category_id = '';
        $this->is_update = false;

        $this->resetErrorBag();
    }

    public function render()
    {
        $categories = CategoryData::collect(Category::all());

        return view('livewire.repository-category-form', compact('categories'));
    }
}
