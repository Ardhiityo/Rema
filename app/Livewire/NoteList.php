<?php

namespace App\Livewire;

use App\Models\Note;
use App\Data\NoteData;
use Livewire\Attributes\On;
use Livewire\Component;

class NoteList extends Component
{
    public int $meta_data_id;

    public function mount($meta_data_id)
    {
        $this->meta_data_id = $meta_data_id;
    }

    #[On('refresh-notes')]
    public function getNotesProperty()
    {
        return NoteData::collect(
            Note::where('meta_data_id', $this->meta_data_id)
                ->orderByDesc('id')->paginate(10)
        );
    }

    public function render()
    {
        return view('livewire.note-list');
    }
}
