<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MetaData;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;

class RepositoryForm extends Component
{
    public bool $is_update = false;
    public string $meta_data_slug = '';

    public function mount()
    {
        if (request()->routeIs('repository.edit')) {
            $this->meta_data_slug = request()->route('meta_data_slug');
            if ($this->metaDataRepository->findBySlug($this->meta_data_slug)) {
                $this->is_update = true;
                session()->forget('meta_data');
            } else {
                return redirect()->route('repository.create');
            }
        }
    }

    #[Computed()]
    public function metaDataRepository()
    {
        return app(MetaDataRepositoryInterface::class);
    }

    #[Computed()]
    public function metaDataSession()
    {
        if (session()->has('meta_data')) {

            $meta_data_session = session()->get('meta_data');

            if ($this->metaDataRepository->findById($meta_data_session->id)) {
                return $meta_data_session;
            }
        }

        return false;
    }

    #[Computed()]
    #[On('refresh-meta-data-session')]
    public function showRepositoryCategoryFrom()
    {
        return $this->metaDataSession;
    }

    #[Computed()]
    #[On('refresh-repository-table')]
    public function showRepositoriesList()
    {
        if ($this->is_update) {
            if ($this->metaDataRepository->findBySlug($this->meta_data_slug)) {
                return true;
            }
        }

        if ($meta_data_session = $this->metaDataSession) {
            $meta_data = MetaData::find($meta_data_session->id);

            if ($meta_data->categories->isNotEmpty()) {
                return true;
            }
        }

        return false;
    }

    public function render()
    {
        return view(
            'livewire.repository-form',
        );
    }
}
