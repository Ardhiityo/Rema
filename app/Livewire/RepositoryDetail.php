<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MetaData;
use App\Data\Metadata\DetailMetadataData;

class RepositoryDetail extends Component
{
    public MetaData $meta_data;
    public string $badge_status_class = '';
    public string $badge_ucfirst = '';

    public function mount(MetaData $meta_data)
    {
        $meta_data->load(
            'author',
            'author.studyProgram',
            'categories'
        );

        $this->meta_data = $meta_data;

        $detail_metadata_data = DetailMetadataData::fromModel($meta_data);
        $this->badge_status_class = $detail_metadata_data->badge_status_class;
        $this->badge_ucfirst = $detail_metadata_data->badge_ucfirst;
    }

    public function render()
    {
        return view('livewire.repository-detail');
    }
}
