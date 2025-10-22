<?php

namespace App\Livewire;

use Throwable;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;

class RepositoryList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Form Start
    public string $title = '';
    public string $year = '';
    public string $visibility = 'public';
    public string $status_filter = 'approve';
    // Form Start

    public int|null $meta_data_id = null;
    public bool $is_author = false;
    public bool $is_admin = false;

    public function mount()
    {
        $user = Auth::user();

        if (Route::is('repository.author.index')) {
            $this->is_author = true;
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
            $this->metaDataRepository->delete($this->meta_data_id);
            session()->flash('repository-list-success', 'The repository was successfully deleted.');
        } catch (Throwable $th) {
            session()->flash('repository-list-failed', $th->getMessage());
        }
    }

    public function resetInput()
    {
        $this->title = '';
        $this->year = '';
        $this->status_filter = 'approve';
        $this->visibility = 'public';

        $this->resetPage();
    }

    public function render()
    {
        $meta_data = $this->metaDataRepository->findByFilters(
            $this->title,
            $this->status_filter,
            $this->year,
            $this->visibility,
            $this->is_author
        );

        return view('livewire.repository-list', compact('meta_data'));
    }
}
