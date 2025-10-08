<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Repository;
use App\Data\RepositoryData;
use App\Models\MetaData;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class RepositoryList extends Component
{
    use WithPagination;

    public string $title = '';
    public string $year = '';
    public string $status_filter = 'approve';
    public int|null $repository_id = null;
    public bool $is_author_only = false;
    public bool $is_admin = false;

    public User $user;

    public function deleteConfirm($repository_slug)
    {
        $repository = Repository::where('slug', $repository_slug)->first();

        $this->repository_id = $repository->id;
    }

    public function mount()
    {
        $user = Auth::user();

        $this->user = $user;

        if (Route::is('repository.author.index')) {
            $this->is_author_only = true;
        }

        if ($user->hasRole('admin')) {
            $this->is_admin = true;
        }
    }

    public function delete()
    {
        $repository = Repository::find($this->repository_id);

        if (Storage::disk('public')->exists($repository->file_path)) {
            Storage::disk('public')->delete($repository->file_path);
        };

        $repository->delete();
    }

    #[On('refresh-repositories')]
    public function getRepositoriesProperty()
    {
        $query = MetaData::query();

        $query->with(['author', 'author.user', 'category', 'author.studyProgram']);

        if ($this->user->hasRole('contributor')) {
            if ($this->is_author_only) {
                $query->where('author_id', $this->user->author->id);
            } else {
                $query
                    ->where('visibility', '!=', 'private');
            }
        }

        $query->where('status', $this->status_filter);

        if ($title = $this->title) {
            $query->whereLike('title', "%$title%");
        }

        if ($year = $this->year) {
            $query->where('year', $year);
        }

        $repositories = $query->orderByDesc('id')->paginate(10);

        return RepositoryData::collect($repositories);
    }

    public function resetInput()
    {
        $this->title = '';
        $this->year = '';
        $this->status_filter = 'approve';

        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.repository-list');
    }
}
