<?php

namespace App\Livewire;

use App\Models\Author;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class RepositoryForm extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $abstract = '';
    public $file_path = null;
    public string $type = '';
    public int|null $author_id = null;
    public string $published_at = '';
    public string $year = '';
    public string $slug = '';

    protected function rules()
    {
        return [
            'title' => ['required'],
            'abstract' => ['required', 'min:3', 'max:2000'],
            'file_path' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'type' => ['required', 'in:thesis,final_project,manual_book'],
            'author_id' => ['required', 'exists:authors,id'],
            'published_at' => ['required'],
            'year' => ['required'],
            'slug' => ['required', 'min:3', 'max:200', 'unique:repositories,slug']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'slug' => 'title',
            'file_path' => 'file'
        ];
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
        $validated = $this->validate();
        dd($validated);
    }

    public function render()
    {
        $authors = Author::get();

        return view('livewire.repository-form', compact('authors'));
    }
}
