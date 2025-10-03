<?php

namespace App\Livewire;

use App\Data\AuthorData;
use App\Data\CategoryData;
use Carbon\Carbon;
use App\Models\Author;
use Livewire\Component;
use App\Models\Category;
use App\Models\Repository;
use Illuminate\Support\Str;
use App\Data\RepositoryData;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RepositoryForm extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $abstract = '';
    public $file_path = null;
    public string $category_id = '';
    public int|null $author_id = null;
    public int|null $repository_id = null;
    public string $slug = '';
    public bool $is_update = false;
    public string $status = '';

    protected function rules()
    {
        return [
            'title' => ['nullable'],
            'slug' =>
            [
                'required',
                'min:3',
                'max:200',
                $this->is_update ? 'unique:repositories,slug,' . $this->repository_id : 'unique:repositories,slug'
            ],
            'abstract' => ['required', 'min:3', 'max:2000'],
            'file_path' => [
                'file',
                'mimes:pdf',
                'max:5120',
                $this->is_update ? 'nullable' : 'required'
            ],
            'category_id' => ['required', 'exists:categories,id'],
            'author_id' => ['required', 'exists:authors,id'],
            'status' => ['in:approve,pending,reject,revision', 'required']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'file_path' => 'file',
            'slug' => 'title',
            'author_id' => 'author'
        ];
    }

    #[Computed()]
    public function formTitle()
    {
        return $this->is_update ? 'Edit Repository' : 'Create New Repository';
    }

    public function mount(string $repository_slug = '')
    {
        if ($repository_slug) {
            $repository_data = RepositoryData::fromModel(
                Repository::with(
                    ['author', 'author.studyProgram']
                )
                    ->where('slug', $repository_slug)
                    ->first()
            );
            $this->repository_id = $repository_data->id;
            $this->title = $repository_data->title;
            $this->slug = Str::slug($repository_data->title);
            $this->abstract = $repository_data->abstract;
            $this->category_id = $repository_data->category_id;
            $this->author_id = $repository_data->author_id;
            $this->is_update = true;
        }
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            $this->author_id = $user->author->id;
            $this->status = 'pending';
        }

        $this->slug = Str::slug($this->title);

        $validated = $this->validate();

        $validated['year'] = Carbon::now()->year;

        $validated['file_path'] = $validated['file_path']->store('repositories', 'public');

        Repository::create($validated);

        $this->resetInput();

        $this->dispatch('refresh-repositories');

        return session()->flash('message', 'The repository was successfully created.');
    }

    public function update()
    {
        $user = Auth::user();

        $repository = Repository::find($this->repository_id);

        if ($user->hasRole('contributor')) {
            $this->status = $repository->status;
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

    public function resetInput()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    public function render()
    {
        $authors = AuthorData::collect(
            Author::with('user')->get()
        );
        $categories = CategoryData::collect(Category::get());

        return view('livewire.repository-form', compact('authors', 'categories'));
    }
}
