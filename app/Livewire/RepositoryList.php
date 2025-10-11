<?php

namespace App\Livewire;

use Livewire\Component;
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
    public string $visibility = 'public';
    public string $status_filter = 'approve';
    public int|null $meta_data_id = null;
    public bool $is_author_only = false;
    public bool $is_admin = false;

    public User $user;

    public function deleteConfirm($meta_data_slug)
    {
        $meta_data = MetaData::where('slug', $meta_data_slug)->first();

        $this->meta_data_id = $meta_data->id;
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
        $meta_data = MetaData::find($this->meta_data_id);

        if ($meta_data->categories->isNotEmpty()) {
            foreach ($meta_data->categories as $key => $category) {
                if ($file_path = $category->pivot->file_path) {
                    if (Storage::disk('public')->exists($file_path)) {
                        Storage::disk('public')->delete($file_path);
                    }
                }
            }
        }

        $meta_data->delete();
    }

    #[On('refresh-repositories')]
    public function getMetaDataProperty()
    {
        $query = MetaData::query();

        $query->with(['author', 'author.user', 'author.studyProgram', 'categories']);

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

        if ($visibility = $this->visibility) {
            $query->where('visibility', $visibility);
        }

        $meta_data = $query->orderByDesc('id')->paginate(10);

        return $meta_data;
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
        return view('livewire.repository-list');
    }
}
