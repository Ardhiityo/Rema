<?php

namespace App\Livewire;

use Throwable;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Data\Note\CreateNoteData;
use App\Data\Note\UpdateNoteData;
use Livewire\Attributes\Computed;
use App\Repositories\Contratcs\NoteRepositoryInterface;

class NoteForm extends Component
{
    public string $message = '';
    public int $note_id;
    public bool $is_update = false;
    public int $meta_data_id;

    public function mount($meta_data_id)
    {
        $this->meta_data_id = $meta_data_id;
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

    #[Computed()]
    public function noteRepository()
    {
        return app(NoteRepositoryInterface::class);
    }

    public function create()
    {
        $validated = $this->validate();

        try {
            $validated['meta_data_id'] = $this->meta_data_id;

            $create_note_data = CreateNoteData::from($validated);

            $this->noteRepository->create($create_note_data);

            $this->resetInput();

            $this->dispatch('refresh-notes');

            session()->flash('note-success', 'The note was successfully created.');
        } catch (Throwable $th) {
            session()->flash('note-failed', $th->getMessage());
        }
    }

    #[On('note-edit')]
    public function edit($note_id)
    {
        try {
            $note = $this->noteRepository->findById($note_id);

            $this->note_id = $note_id;
            $this->message = $note->message;

            $this->is_update = true;
        } catch (Throwable $th) {
            session()->flash('note-failed', $th->getMessage());
        }
    }

    public function update()
    {
        $validated = $this->validate();

        try {
            $update_note_data = UpdateNoteData::from($validated);

            $this->noteRepository->update($update_note_data, $this->note_id);

            $this->resetInput();

            $this->is_update = false;

            $this->dispatch('refresh-notes');

            session()->flash('note-success', 'The note was successfully updated.');
        } catch (Throwable $th) {
            session()->flash('note-failed', $th->getMessage());
        }
    }

    #[On('note-delete-confirm')]
    public function deleteConfirm($note_id)
    {
        $this->note_id = $note_id;
    }

    #[On('note-delete')]
    public function delete()
    {
        try {
            $this->noteRepository->delete($this->note_id);

            $this->dispatch('refresh-notes');

            session()->flash('note-success', 'The note was successfully deleted.');
        } catch (Throwable $th) {
            session()->flash('note-failed', $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.note-form');
    }
}
