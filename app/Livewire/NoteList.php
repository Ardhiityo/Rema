<?php

namespace App\Livewire;

use App\Models\Note;
use App\Data\NoteData;
use Livewire\Attributes\On;
use Livewire\Component;

class NoteList extends Component
{
    public int $repository_id;

    public function mount($repository_id)
    {
        $this->repository_id = $repository_id;
    }

    #[On('refresh-notes')]
    public function getNotesProperty()
    {
        return NoteData::collect(
            Note::where('repository_id', $this->repository_id)
                ->orderByDesc('id')->paginate(10)
        );
    }

    public function render()
    {
        return view('livewire.note-list');
    }
}
