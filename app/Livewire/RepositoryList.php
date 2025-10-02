<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Repository;
use App\Data\RepositoryData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RepositoryList extends Component
{
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

    public function render()
    {
        $query = Repository::query();

        $query->with(['author', 'author.user', 'category', 'author.studyProgram']);

        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            $query->where('author_id', $user->author->id);
        }

        $query->where('status', $this->status_filter);

        if ($keyword = $this->keyword) {
            $query->whereLike('title', "%$keyword%");
        }
        Log::info(json_encode($query->get(), JSON_PRETTY_PRINT));
        $repositories = RepositoryData::collect(
            $query->orderByDesc('id')->paginate(10)
        );

        return view('livewire.repository-list', compact('repositories'));
    }
}
