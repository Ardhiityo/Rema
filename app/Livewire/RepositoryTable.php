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
    public bool $is_approve = false;

    public function mount($meta_data_id = null, $is_approve = false)
    {
        $this->is_approve = $is_approve;

        if ($meta_data_id) {
            $this->meta_data_id = $meta_data_id;
        }
    }

    #[Computed()]
    public function islockForm()
    {
        if (Auth::user()->hasRole('contributor')) {
            return $this->is_approve;
        }
    }

    #[Computed()]
    public function metaDataRepository()
    {
        return app(MetaDataRepositoryInterface::class);
    }

    #[Computed()]
    #[On('refresh-repository-table')]
    public function repositories()
    {
        $relations = ['categories'];

        if ($meta_data_id = $this->metaDataSession ? $this->metaDataSession->id : $this->meta_data_id) {
            return $this->metaDataRepository->findById($meta_data_id, $relations);
        }
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
