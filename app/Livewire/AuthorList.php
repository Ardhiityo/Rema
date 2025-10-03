<?php

namespace App\Livewire;

use Livewire\Component;
use App\Data\AuthorData;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class AuthorList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public string $status_filter = 'approve';
    public string $keyword = '';

    #[On('refresh-authors')]
    public function getAuthorsProperty()
    {
        $query = \App\Models\Author::query();

        $query->where('status', $this->status_filter);

        if ($keyword = $this->keyword) {
            $query->whereLike('name', "%$keyword%")->orWhere('nim', $keyword);
        }

        $query->with(['studyProgram', 'user']);

        return AuthorData::collect(
            $query->orderByDesc('id')->paginate(10)
        );
    }

    public function render()
    {
        return view('livewire.author-list');
    }
}
