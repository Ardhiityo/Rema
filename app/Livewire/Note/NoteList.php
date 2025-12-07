<?php

namespace App\Livewire\Note;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Repositories\Contratcs\NoteRepositoryInterface;
use Livewire\WithPagination;

class NoteList extends Component
{
    use WithPagination;

    public int $meta_data_id;

    public function mount($meta_data_id)
    {
        $this->meta_data_id = $meta_data_id;
    }

    #[On('refresh-notes')]
    public function getNotesProperty(NoteRepositoryInterface $noteRepository)
    {
        return $noteRepository->findByMetaDataId($this->meta_data_id);
    }

    public function render()
    {
        return view('livewire.note.list');
    }
}
