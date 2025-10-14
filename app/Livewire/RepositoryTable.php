<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;

class RepositoryTable extends Component
{
    public int|null $meta_data_id = null;
    public bool $is_update = false;
    public bool $is_approve = false;

    public function mount()
    {
        if ($meta_data_session = $this->metaDataSession) {
            $this->meta_data_id = $meta_data_session->id;
            $this->is_update = true;
        }

        if (request()->routeIs('repository.edit')) {
            $meta_data_slug = request()->route('meta_data_slug');
            $meta_data = $this->metaDataRepository->findBySlug($meta_data_slug);
            $this->meta_data_id = $meta_data->id;
            $this->is_approve = $meta_data->status == 'approve' ? true : false;
            $this->is_update = true;
        }
    }

    #[Computed()]
    public function islockForm()
    {
        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            return $this->is_approve;
        }
    }

    #[On('refresh-repository-table')]
    public function getRepositoriesProperty()
    {
        $relations = ['categories'];

        if ($this->is_update) {
            $meta_data_data = $this->metaDataRepository->findById($this->meta_data_id, $relations);
            return $meta_data_data;
        }

        if ($meta_data_session = $this->metaDataSession) {
            $meta_data_data = $this->metaDataRepository->findById($meta_data_session->id, $relations);
            if ($meta_data_data->categories->toCollection()->isNotEmpty()) {
                return $meta_data_data;
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

    public function render()
    {
        return view('livewire.repository-table');
    }
}
