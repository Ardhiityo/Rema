<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Author;
use Livewire\Component;
use App\Data\AuthorData;
use App\Models\Category;
use App\Models\Metadata;
use App\Data\CategoryData;
use App\Data\MetadataData;
use App\Models\Repository;
use Illuminate\Support\Str;
use App\Data\RepositoryData;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\DataCollection;
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
    public int|string $repository_id = '';
    public string $slug = '';
    public string $status = '';
    public string $visibility = '';
    public bool $is_update = false;
    public bool $is_lock_meta_data = false;

    protected function rulesMetaData()
    {
        return [
            'title' => ['required'],
            'slug' =>
            [
                'required',
                'min:3',
                'max:200',
                $this->is_update ? 'unique:meta_data,slug,' . $this->repository_id : 'unique:meta_data,slug'
            ],
            'abstract' => ['required', 'min:3', 'max:2000'],
            'author_id' => ['required', 'exists:authors,id'],
            'status' => ['required', 'in:approve,pending,reject,revision'],
            'visibility' => ['required', 'in:private,protected,public']
        ];
    }

    protected function rulesRepository()
    {
        return [
            'file_path' => [
                'file',
                'mimes:pdf',
                'max:5120',
                $this->is_update ? 'nullable' : 'required'
            ],
            'category_id' => ['required', 'exists:categories,id'],
            'meta_data_id' => ['required', 'exists:meta_data,id']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'file_path' => 'file',
            'slug' => 'title',
            'author_id' => 'author',
            'category_id' => 'category'
        ];
    }

    #[Computed()]
    public function metaDataTitle()
    {
        return $this->is_update ? 'Edit Meta Data' : 'Create Meta Data';
    }

    #[Computed()]
    public function repositoryTitle()
    {
        return $this->is_update ? 'Edit Repository Data' : 'Create Repository';
    }

    public function mount(string $meta_data_slug = '')
    {
        if ($meta_data_slug) {
            $meta_data =  Metadata::with(
                ['author', 'author.studyProgram']
            )
                ->where('slug', $meta_data_slug)
                ->first();

            $this->authorize('update', $meta_data);

            $metadata_data = MetadataData::fromModel($meta_data);

            $this->repository_id = $metadata_data->id;
            $this->title = $metadata_data->title;
            $this->slug = Str::slug($metadata_data->title);
            $this->abstract = $metadata_data->abstract;
            $this->author_id = $metadata_data->author_id;
            $this->status = $metadata_data->status;
            $this->visibility = $meta_data->visibility;
            $this->is_update = true;
        }
        if (session()->has('meta_data')) {
            $meta_data = session()->get('meta_data');
            // dd($meta_data);
            $this->title = data_get($meta_data, 'title');
            $this->abstract = data_get($meta_data, 'abstract');
            $this->author_id = data_get($meta_data, 'author_id');
            $this->status = data_get($meta_data, 'status');
            $this->visibility = data_get($meta_data, 'visibility');
            $this->is_lock_meta_data = true;
        }
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

        $meta_data = Metadata::create($validated);

        session()->put('meta_data', $meta_data);

        $this->is_lock_meta_data = true;

        return session()->flash('succes-meta-data', 'The meta data was successfully created.');
    }

    public function getMetaDataProperty()
    {
        $meta_data = session()->get('meta_data', false);

        return $meta_data;
    }

    public function createRepository()
    {
        $this->meta_data_id = data_get(session('meta_data'), 'id');

        $validated = $this->validate($this->rulesRepository());

        $exists = Repository::where('category_id', $validated['category_id'])->where('meta_data_id', $validated['meta_data_id'])->exists();

        if ($exists) {
            $this->addError('category_id', 'category already exists');
            return;
        }

        $validated['file_path'] = $validated['file_path']->store('repositories', 'public');

        Repository::create($validated);

        $this->resetInput();

        return session()->flash('succes-repository', 'The repository was successfully created.');
    }

    public function update()
    {
        $user = Auth::user();

        $repository = Repository::find($this->repository_id);

        if ($user->hasRole('contributor')) {
            $this->status = $repository->status;
            $this->visibility = $repository->visibility;
        }

        $validated = $this->validate();

        if ($validated['file_path']) {
            if (Storage::disk('public')->exists($repository->file_path)) {
                Storage::disk('public')->delete($repository->file_path);
            };
            $validated['file_path'] = $validated['file_path']->store('repositories', 'public');
        } else {
            $validated['file_path'] = $repository->file_path;
        }

        $repository->update($validated);

        $this->dispatch('refresh-repositories');

        return session()->flash('message', 'The repository was successfully updated.');
    }

    public function createNewMetaData()
    {
        session()->forget('meta_data');
        $this->is_lock_meta_data = false;
    }

    public function resetInput()
    {
        $this->title = '';
        $this->abstract = '';
        $this->file_path = null;
        $this->author_id = '';
        $this->category_id = '';
        $this->status = '';

        $this->resetErrorBag();
    }

    public function resetInputRepository()
    {
        $this->file_path = null;
        $this->category_id = '';

        $this->resetErrorBag();
    }

    public function getRepositoriesProperty()
    {
        $repositories = Repository::with('category')
            ->where('meta_data_id', data_get($this->meta_data, 'id'))
            ->get();

        return (new DataCollection(RepositoryData::class, $repositories))->toCollection();
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
