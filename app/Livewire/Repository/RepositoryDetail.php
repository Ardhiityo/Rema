<?php

namespace App\Livewire\Repository;

use Livewire\Component;
use App\Models\Metadata;
use App\Data\Metadata\DetailMetadataData;

class RepositoryDetail extends Component
{
    public Metadata $meta_data;
    public string $badge_status_class = '';
    public string $badge_ucfirst = '';
    public string $avatar = '';
    public string $study_program = '';

    public function mount(Metadata $meta_data)
    {
        $meta_data->load('categories', 'author.user', 'studyProgram');
        $this->meta_data = $meta_data;

        $detail_metadata_data = DetailMetadataData::fromModel($meta_data);
        $this->study_program = $detail_metadata_data->study_program;
        $this->avatar = $detail_metadata_data->avatar;
        $this->badge_status_class = $detail_metadata_data->badge_status_class;
        $this->badge_ucfirst = $detail_metadata_data->badge_ucfirst;
    }

    public function render()
    {
        return view('livewire.repository.detail');
    }
}
