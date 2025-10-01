<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Author;
use Livewire\Component;
use App\Models\Repository;
use Illuminate\Support\Str;
use App\Data\RepositoryData;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use App\Rules\RepositoryUpdateRule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RepositoryForm extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $abstract = '';
    public $file_path = null;
    public string $type = '';
    public int|null $author_id = null;
    public string $published_at = '';
    public int|null $repository_id = null;
    public string $year = '';
    public string $slug = '';
    public bool $is_update = false;

    protected function rules()
    {
        return [
            'title' => ['nullable'],
            'slug' => ['required', 'min:3', 'max:200', new RepositoryUpdateRule(
                is_update: $this->is_update,
                repository_id: $this->repository_id
            )],
            'abstract' => ['required', 'min:3', 'max:2000'],
            'file_path' => [$this->is_update ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:5120'],
            'type' => ['required', 'in:thesis,final_project,manual_book'],
            'author_id' => ['required', 'exists:authors,id'],
            'published_at' => ['required'],
            'year' => ['required']
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
            $this->type = $repository_data->original_type;
            $this->author_id = $repository_data->author_id;
            $this->published_at = $repository_data->publised_at_to_ymd;
            $this->year = $repository_data->published_at_year;
            $this->is_update = true;
        } else {
            $date =  Carbon::now();
            $this->published_at = $date->format('Y-m-d');
            $this->year = $date->year;
        }
    }

    public function updatedTitle($value)
    {
        $slug = Str::slug($value);
        $this->slug = $slug;
    }

    public function updatedPublishedAt($value)
    {
        $year = Carbon::parse($value)->year;
        $this->year = $year;
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            $this->author_id = $user->author->id;
            $this->published_at = Carbon::now();
            $this->year = Carbon::now()->year;
        }

        $validated = $this->validate();

        $validated['file_path'] = $validated['file_path']->store('repositories', 'public');

        if ($user->hasRole('admin')) {
            $validated['status'] = 'approve';
        }

        Repository::create($validated);

        return redirect()->route('repository.index');
    }

    public function update()
    {
        $validated = $this->validate();

        $repository = Repository::find($this->repository_id);

        if ($validated['file_path']) {
            if (Storage::disk('public')->exists($repository->file_path)) {
                Storage::disk('public')->delete($repository->file_path);
            };
            $validated['file_path'] = $validated['file_path']->store('repositories', 'public');
        } else {
            $validated['file_path'] = $repository->file_path;
        }

        $repository->update($validated);

        return redirect()->route('repository.index');
    }

    public function resetInput()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    public function render()
    {
        $authors = Author::get();

        return view('livewire.repository-form', compact('authors'));
    }
}
