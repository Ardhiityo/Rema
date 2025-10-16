<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Repositories\Contratcs\AuthorRepositoryInterface;

class AuthorList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $status_filter = 'approve';
    public string $keyword = '';

    #[On('refresh-authors')]
    public function getAuthorsProperty(AuthorRepositoryInterface $authorRepository)
    {
        return $authorRepository->findByFilters($this->status_filter, $this->keyword);
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
