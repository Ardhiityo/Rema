<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Repositories\Contratcs\CategoryRepositoryInterface;

class CategoryList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $keyword = '';

    public function resetInput()
    {
        $this->keyword = '';

        $this->resetPage();
    }

    #[On('refresh-categories')]
    public function getCategoriesProperty(CategoryRepositoryInterface $categoryRepository)
    {
        return $categoryRepository->findByFilters($this->keyword);
    }

    public function render()
    {
        return view('livewire.category-list');
    }
}
