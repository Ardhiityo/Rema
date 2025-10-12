<?php

namespace App\Livewire;

use App\Models\Author;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Data\Author\AuthorListData;

class AuthorList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public string $status_filter = 'approve';
    public string $keyword = '';

    #[On('refresh-authors')]
    public function getAuthorsProperty()
    {
        $query = Author::query();

        $query->where('status', $this->status_filter);

        if ($keyword = $this->keyword) {
            $query->whereHas(
                'user',
                fn($query) => $query->whereLike('name', "%$keyword%")
            )
                ->orWhere('nim', $keyword);
        }

        $query->with(['studyProgram', 'user']);

        return AuthorListData::collect(
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
        return view('livewire.author-list');
    }
}
