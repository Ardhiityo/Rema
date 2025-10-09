<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Metadata;

class RepositoryDetail extends Component
{
    public MetaData $meta_data;

    public function mount(Metadata $meta_data)
    {
        $this->meta_data = $meta_data->load(
            'author',
            'author.studyProgram',
            'categories'
        );
    }

    public function render()
    {
        return view('livewire.repository-detail');
    }
}
