<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Author;
use Livewire\Component;
use App\Data\Author\AuthorData;
use App\Models\Category;
use App\Models\MetaData;
use App\Services\PdfWatermarkService;
use App\Data\Category\CategoryData;
use App\Models\Repository;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RepositoryForm extends Component
{
    use WithFileUploads, AuthorizesRequests;

    public string $title = '';
    public string $abstract = '';
    public $file_path = null;
    public int|string $author_id = '';
    public int|null $meta_data_id = null;
    public int|string $category_id = '';
    public string|null $file_path_update = null;
    public int|null $category_id_update = null;
    public int|null $category_id_delete = null;
    public int|string $repository_id = '';
    public string $slug = '';
    public string $status = '';
    public string $visibility = '';

    public bool $is_update = false;
    public bool $is_approve = false;
    public bool $is_edit_meta_data = false;
    public bool $is_edit_repository = false;

    protected function validationAttributes()
    {
        return [
            'file_path' => 'file',
            'slug' => 'title',
            'author_id' => 'author',
            'category_id' => 'category'
        ];
    }

    public function mount(string $meta_data_slug = '')
    {
        if ($meta_data_slug) {
            $meta_data =  MetaData::with(
                ['author', 'author.studyProgram']
            )
                ->where('slug', $meta_data_slug)
                ->first();

            $this->authorize('update', $meta_data);
            $this->createNewForm();
            $this->meta_data_id = $meta_data->id;
            $this->title = $meta_data->title;
            $this->slug = $meta_data->slug;
            $this->abstract = $meta_data->abstract;
            $this->author_id = $meta_data->author_id;
            $this->status = $meta_data->status;
            $this->visibility = $meta_data->visibility;
            $this->is_approve = $meta_data->status == 'approve' ? true : false;
            $this->is_update = true;
        }
        if ($data = $this->meta_data_session) {
            $meta_data_session = $data;
            $this->meta_data_id = data_get($meta_data_session, 'id');
            $this->title = data_get($meta_data_session, 'title');
            $this->abstract = data_get($meta_data_session, 'abstract');
            $this->author_id = data_get($meta_data_session, 'author_id');
            $this->status = data_get($meta_data_session, 'status');
            $this->visibility = data_get($meta_data_session, 'visibility');
        }
    }

    public function createNewForm()
    {
        session()->forget('meta_data');

        $this->resetInputMetaData();
    }

    public function getMetaDataSessionProperty()
    {
        if (session()->has('meta_data')) {
            $meta_data_session = session()->get('meta_data');
            if (MetaData::find($meta_data_session['id'])) {
                return $meta_data_session;
            }
            return $this->createNewForm();
        }
        return false;
    }

    #[Computed()]
    public function islockForm()
    {
        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            return $this->is_approve;
        }
    }

    #[Computed()]
    public function metaDataTitle()
    {
        if ($this->is_update) {
            return $this->is_update ? 'Edit Meta Data' : 'Create Meta Data';
        }
        return $this->isMetaDataEdit() ? 'Edit Meta Data' : 'Create Meta Data';
    }

    #[Computed()]
    public function isMetaDataEdit()
    {
        return $this->meta_data_session;
    }

    protected function rulesMetaData()
    {
        return [
            'title' => ['required'],
            'slug' =>
            [
                'required',
                'min:3',
                'max:200',
                $this->is_edit_meta_data ? 'unique:meta_data,slug,' . $this->meta_data_id : 'unique:meta_data,slug'
            ],
            'abstract' => ['required', 'min:3', 'max:2000'],
            'author_id' => ['required', 'exists:authors,id'],
            'status' => ['required', 'in:approve,pending,reject,revision'],
            'visibility' => ['required', 'in:private,protected,public']
        ];
    }

    public function createMetaData()
    {
        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            $this->author_id = $user->author->id;
            $this->status = 'pending';
            $this->visibility = 'private';
        }

        $this->slug = Str::slug($this->title);

        $validated = $this->validate($this->rulesMetaData());

        $validated['year'] = Carbon::now()->year;

        $meta_data = MetaData::create($validated);

        $this->meta_data_id = $meta_data->id;

        if (!$this->is_update) {
            session()->put('meta_data', $meta_data);
        }

        return session()->flash('succes-meta-data', 'The meta data was successfully created.');
    }

    public function updateMetaData()
    {
        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            $this->author_id = $user->author->id;
            $this->status = 'pending';
            $this->visibility = 'private';
        }

        $this->slug = Str::slug($this->title);

        $this->is_edit_meta_data = true;

        $validated = $this->validate($this->rulesMetaData());

        $meta_data = MetaData::find($this->meta_data_id);

        $meta_data->update($validated);

        if (!$this->is_update) {
            session()->put('meta_data', $meta_data);
        }

        return session()->flash('succes-meta-data', 'The meta data was successfully updated.');
    }

    public function resetInputMetaData()
    {
        $this->title = '';
        $this->abstract = '';
        $this->author_id = '';
        $this->status = '';
        $this->visibility = '';
        $this->resetErrorBag();
    }



    #[Computed()]
    public function repositoryTitle()
    {
        return $this->is_edit_repository ? 'Edit Repository' : 'Create Repository';
    }

    #[Computed()]
    public function showRepositoryFrom()
    {
        if ($this->is_update) {
            return !$this->islockForm();
        }
        return $this->meta_data_session;
    }

    #[Computed()]
    public function showRepositoriesList()
    {
        if ($this->is_update) {
            if (MetaData::find($this->meta_data_id)->categories->isNotEmpty()) {
                return true;
            }
        }
        if ($data = $this->meta_data_session) {
            if ($meta_data_session = $data) {
                $meta_data = MetaData::find($meta_data_session['id']);
                if ($meta_data?->categories->isNotEmpty()) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function rulesRepository()
    {
        return [
            'file_path' => [
                $this->is_edit_repository ? 'nullable' : 'required',
                'file',
                'mimes:pdf',
                'max:7000'
            ],
            'category_id' => ['required', 'exists:categories,id'],
            'meta_data_id' => ['required', 'exists:meta_data,id']
        ];
    }

    public function createRepository()
    {
        if (!$this->is_update) {
            $this->meta_data_id = data_get(session('meta_data'), 'id');
        }

        $validated = $this->validate($this->rulesRepository());

        $author_nim = MetaData::find($validated['meta_data_id'])->author->nim;

        $exists = Repository::where('category_id', $validated['category_id'])->where('meta_data_id', $validated['meta_data_id'])->exists();

        if ($exists) {
            $this->addError('category_id', 'category already exists');
            return;
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

        Repository::create($validated);

        $this->resetInputRepository();

        return session()->flash('repository-success', 'The repository was successfully created.');
    }

    public function editRepository($meta_data_slug, $category_slug)
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
        $this->category_id_update = $repository->category_id;
        $this->category_id = $repository->category_id;
        $this->file_path_update = $repository->file_path;
        $this->is_edit_repository = true;
    }

    public function updateRepository()
    {
        $validated = $this->validate($this->rulesRepository());

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

        $this->resetInputRepository();

        $this->is_edit_repository = false;

        return session()->flash('repository-success', 'The repository was successfully updated.');
    }

    public function deleteConfirmRepository($meta_data_slug, $category_slug)
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

    public function deleteRepository()
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
            $this->resetInputRepository();
        }

        return session()->flash('repository-success', 'The repository was successfully deleted.');
    }

    public function resetInputRepository()
    {
        $this->file_path = null;
        $this->category_id = '';
        $this->is_edit_repository = false;

        $this->resetErrorBag();
    }

    public function getRepositoriesProperty()
    {
        if ($this->is_update) {
            $meta_data = MetaData::find($this->meta_data_id);
            return $meta_data;
        }
        if ($meta_data_session = $this->meta_data_session) {
            $meta_data = MetaData::find($meta_data_session['id']);
            if ($meta_data?->categories) {
                return $meta_data;
            }
            return false;
        }
    }




    public function render()
    {
        $authors = AuthorData::collect(
            Author::with('user')
                ->where('status', 'approve')->get()
        );

        $categories = CategoryData::collect(Category::all());

        return view(
            'livewire.repository-form',
            compact('authors', 'categories')
        );
    }
}
