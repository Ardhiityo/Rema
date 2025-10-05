<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Repository;
use App\Data\RepositoryData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class RepositoryList extends Component
{
    use WithPagination;

    public string $keyword = '';
    public string $title = '';
    public string $status_filter = 'approve';

    public int|null $repository_id = null;

    public function deleteConfirm($repository_slug)
    {
        $repository = Repository::where('slug', $repository_slug)->first();

        $this->repository_id = $repository->id;

        $this->title = $repository->title;
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
        $query = Repository::query();

        $query->with(['author', 'author.user', 'category', 'author.studyProgram']);

        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            $query
                ->where('status', 'approve')
                ->where('visibility', 'protected')
                ->orWhere('visibility', 'public');
        }

        $query->where('status', $this->status_filter);

        if ($keyword = $this->keyword) {
            $query->whereLike('title', "%$keyword%");
        }

        return RepositoryData::collect(
            $query->orderByDesc('id')->paginate(10)
        );
    }

    public function resetInput()
    {
        $this->keyword = '';
        $this->status_filter = 'approve';

        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.repository-list');
    }
}
