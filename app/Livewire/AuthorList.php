<?php

namespace App\Livewire;

use Livewire\Component;
use App\Data\AuthorData;
use Livewire\WithPagination;

class AuthorList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public string $status_filter = 'approve';
    public string $keyword = '';

    public function render()
    {
        $query = \App\Models\Author::query();

        $query->where('status', $this->status_filter);

        if ($keyword = $this->keyword) {
            $query->whereLike('name', "%$keyword%")->orWhere('nim', $keyword);
        }

        $query->with(['studyProgram', 'user']);

        $authors = AuthorData::collect(
            $query->orderByDesc('id')->paginate(10)
        );

        return view('livewire.author-list', compact('authors'));
    }
}
