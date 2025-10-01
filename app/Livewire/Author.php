<?php

namespace App\Livewire;

use Livewire\Component;
use App\Data\AuthorData;
use App\Models\StudyProgram;
use Livewire\WithPagination;
use App\Data\StudyProgramData;
use App\Rules\AuthorUpdateRule;
use Livewire\Attributes\Computed;

class Author extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $keyword = '';
    public string $name = '';
    public string $nim = '';
    public int|null $study_program_id = null;
    public int|null $author_id = null;
    public bool $is_update = false;

    protected function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:50'],
            'nim' => ['required', 'min:3', 'max:50', new AuthorUpdateRule(
                $this->is_update,
                $this->author_id
            )],
            'study_program_id' => ['required', 'exists:study_programs,id']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'name' => 'author'
        ];
    }

    #[Computed()]
    public function formTitle()
    {
        return $this->is_update ? 'Edit Author' : 'Create New Author';
    }

    public function create()
    {
        $validated = $this->validate();

        \App\Models\Author::create($validated);

        return redirect()->route('author.index');
    }

    public function edit($author_id)
    {
        $author = \App\Models\Author::find($author_id);

        $this->author_id = $author->id;
        $this->name = $author->name;
        $this->nim = $author->nim;
        $this->study_program_id = $author->study_program_id;

        $this->is_update = true;
    }

    public function update()
    {
        $validated = $this->validate();

        $author = \App\Models\Author::find($this->author_id);

        $author->update($validated);

        $this->is_update = false;

        $this->resetInput();
    }

    public function deleteConfirm($author_id)
    {
        $author = \App\Models\Author::find($author_id);

        $this->author_id = $author->id;
        $this->nim = $author->nim;
    }

    public function delete()
    {
        \App\Models\Author::find($this->author_id)->delete();
    }

    public function resetInput()
    {
        $this->reset();
        $this->resetPage();
        $this->resetErrorBag();
    }

    public function render()
    {
        $query = \App\Models\Author::query();

        if ($keyword = $this->keyword) {
            $query->whereLike('name', "%$keyword%")->orWhere('nim', $keyword);
        }

        $query->with('studyProgram');

        $authors = AuthorData::collect($query->paginate(10));
        $study_programs = StudyProgramData::collect(StudyProgram::get());

        return view('livewire.author', compact('authors', 'study_programs'));
    }
}
