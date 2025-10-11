<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Data\Category\CategoryData;

class CategoryList extends Component
{
    use WithPagination;

    public string $keyword = '';

    public function resetInput()
    {
        $this->keyword = '';

        $this->resetPage();
    }

    #[On('refresh-categories')]
    public function getCategoriesProperty()
    {
        $query = Category::query();

        if ($keyword = $this->keyword) {
            $query->whereLike('name', "%$keyword%");
        }

        return CategoryData::collect(
            $query->orderByDesc('id')->paginate(10)
        );
    }

    public function render()
    {
        return view('livewire.category-list');
    }
}
