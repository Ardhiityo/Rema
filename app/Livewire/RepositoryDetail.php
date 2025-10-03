<?php

namespace App\Livewire;

use App\Data\NoteData;
use App\Models\Note;
use Livewire\Component;
use App\Models\Repository;
use App\Data\RepositoryData;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;

class RepositoryDetail extends Component
{
    public string $title = '';
    public string $abstract = '';
    public string $category = '';
    public string $author = '';
    public string|null $nim = '';
    public string $created_at = '';
    public string|null $study_program = '';
    public string $slug = '';
    public string $message = '';
    public int $repository_id;
    public int $note_id;
    public bool $is_update = false;

    protected function rules()
    {
        return [
            'message' => ['required', 'min:3', 'max:500']
        ];
    }

    public function mount(Repository $repository)
    {
        $repository_data = RepositoryData::fromModel(
            $repository->load('author', 'author.studyProgram')
        );

        $this->repository_id = $repository_data->id;
        $this->title = $repository_data->title;
        $this->abstract = $repository_data->abstract;
        $this->category = $repository_data->category_name;
        $this->author = $repository_data->author_name;
        $this->nim = $repository_data->nim;
        $this->slug = $repository_data->slug;
        $this->created_at = $repository_data->created_at;
        $this->study_program = $repository_data->study_program;
    }

    #[Computed()]
    public function formTitle()
    {
        return $this->is_update ? 'Edit Note' : 'Create New Note';
    }

    public function create()
    {
        $validated = $this->validate();

        $validated['repository_id'] = $this->repository_id;

        Note::create($validated);
    }

    public function edit($note_id)
    {
        $note = Note::find($note_id);

        $this->note_id = $note_id;
        $this->message = $note->message;

        $this->is_update = true;
    }

    public function resetInput()
    {
        $this->message = '';
        $this->resetErrorBag();
    }

    public function update()
    {
        $validated = $this->validate();

        Note::find($this->note_id)->update($validated);

        $this->resetInput();

        $this->is_update = false;

        return session()->flash('message', 'The note was successfully created.');
    }

    public function deleteConfirm($note_id)
    {
        $this->note_id = $note_id;
    }

    public function delete()
    {
        Note::destroy($this->note_id);

        return session()->flash('message', 'The note was successfully deleted.');
    }

    public function render()
    {
        $notes = NoteData::collect(
            Note::where('repository_id', $this->repository_id)
                ->paginate(10)
        );

        return view('livewire.repository-detail', compact('notes'));
    }
}
