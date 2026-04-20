<?php

namespace App\Livewire\Repository\List;

use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class MetaDataCategoryList extends Component
{
    public ?int $meta_data_id = null;

    public function mount($meta_data_id = null)
    {
        if ($meta_data_id) {
            $this->meta_data_id = $meta_data_id;
        }
    }

    #[Computed()]
    public function metaDataRepository()
    {
        return app(MetaDataRepositoryInterface::class);
    }

    #[Computed()]
    #[On('refresh-meta-data-category')]
    public function repositories()
    {
        if ($meta_data_id = $this->metaDataSession ? $this->metaDataSession->id : $this->meta_data_id) {
            return $this->metaDataRepository->findById(
                meta_data_id: $meta_data_id,
                relations: ['categories']
            );
        }
    }

    #[Computed()]
    public function metaDataSession()
    {
        if (Session::has('meta_data')) {
            $meta_data_session = Session::get('meta_data');

            if ($this->metaDataRepository->findById($meta_data_session->id)) {
                return $meta_data_session;
            }
        }

        return false;
    }

    public function render()
    {
        return view('livewire.repository.list.meta-data-category');
    }
}
