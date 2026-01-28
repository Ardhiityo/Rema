<?php

namespace App\Livewire\Repository;

use Livewire\Component;
use App\Models\MetaData;
use App\Data\Metadata\DetailMetadataData;

class RepositoryDetail extends Component
{
    public MetaData $meta_data;
    public string $badge_status_class = '';
    public string $badge_ucfirst = '';
    public string $avatar = '';

    public function mount(MetaData $meta_data)
    {
        $meta_data->load('categories', 'author.user');
        $this->meta_data = $meta_data;

        $detail_metadata_data = DetailMetadataData::fromModel($meta_data);
        $this->avatar = $detail_metadata_data->avatar;
        $this->badge_status_class = $detail_metadata_data->badge_status_class;
        $this->badge_ucfirst = $detail_metadata_data->badge_ucfirst;
    }

    public function render()
    {
        return view('livewire.repository.detail');
    }
}
