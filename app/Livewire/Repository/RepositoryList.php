<?php

namespace App\Livewire\Repository;

use Throwable;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RepositoryList extends Component
{
    use WithPagination, AuthorizesRequests;

    // Form Start
    public string $keyword = '';
    public string $year = '';
    public string $visibility = 'public';
    public string $status_filter = 'approve';
    // Form Start

    public int|null $meta_data_id = null;
    public bool $is_author = false;
    public bool $is_admin = false;
    public bool $is_master_data = false;

    public function mount()
    {
        $user = Auth::user();

        if (Route::is('repository.author.index')) {
            $this->is_author = true;
        }

        if (Route::is('repository.index')) {
            $this->is_master_data = true;
        }

        if ($user->hasRole('admin')) {
            $this->is_admin = true;
        }
    }

    #[Computed()]
    public function metaDataRepository()
    {
        return app(MetaDataRepositoryInterface::class);
    }

    public function deleteConfirm($meta_data_slug)
    {
        try {
            $meta_data = $this->metaDataRepository->findBySlug($meta_data_slug);

            $this->meta_data_id = $meta_data->id;
        } catch (Throwable $th) {
            session()->flash('repository-list-failed', $th->getMessage());
        }
    }

    public function delete()
    {
        try {
            $meta_data = $this->metaDataRepository->findById($this->meta_data_id);

            $this->authorize('delete', $meta_data->toModel());

            $this->metaDataRepository->delete($this->meta_data_id);

            session()->flash('repository-list-success', 'The repository was successfully deleted.');
        } catch (Throwable $th) {
            session()->flash('repository-list-failed', $th->getMessage());
        }
    }

    public function resetInput()
    {
        $this->keyword = '';
        $this->year = '';
        $this->status_filter = 'approve';
        $this->visibility = 'public';

        $this->resetPage();
    }

    public function render()
    {
        $meta_data = $this->metaDataRepository->findByFilters(
            $this->keyword,
            $this->status_filter,
            $this->year,
            $this->visibility,
            $this->is_master_data
        );

        return view('livewire.repository.list', compact('meta_data'));
    }
}
