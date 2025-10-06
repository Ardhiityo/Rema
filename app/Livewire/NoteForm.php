<?php

namespace App\Livewire;

use App\Models\Note;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;

class NoteForm extends Component
{
    public string $message = '';
    public int $note_id;
    public bool $is_update = false;

    public int $repository_id;

    public function mount($repository_id)
    {
        $this->repository_id = $repository_id;
    }

    #[Computed()]
    public function formTitle()
    {
        return $this->is_update ? 'Edit Note' : 'Create New Note';
    }

    protected function rules()
    {
        return [
            'message' => ['required', 'min:3', 'max:500']
        ];
    }

    public function resetInput()
    {
        $this->message = '';
        $this->resetErrorBag();
    }

    public function create()
    {
        $validated = $this->validate();

        $validated['repository_id'] = $this->repository_id;

        Note::create($validated);

        $this->resetInput();

        $this->dispatch('refresh-notes');

        return session()->flash('message', 'The note was successfully created.');
    }

    #[On('note-edit')]
    public function edit($note_id)
    {
        $note = Note::find($note_id);

        $this->note_id = $note_id;
        $this->message = $note->message;

        $this->is_update = true;
    }

    public function update()
    {
        $validated = $this->validate();

        Note::find($this->note_id)->update($validated);

        $this->resetInput();

        $this->is_update = false;

        $this->dispatch('refresh-notes');

        return session()->flash('message', 'The note was successfully updated.');
    }

    #[On('note-delete-confirm')]
    public function deleteConfirm($note_id)
    {
        $this->note_id = $note_id;
    }

    #[On('note-delete')]
    public function delete()
    {
        Note::destroy($this->note_id);

        $this->dispatch('refresh-notes');

        return session()->flash('message', 'The note was successfully deleted.');
    }

    public function render()
    {
        return view('livewire.note-form');
    }
}
