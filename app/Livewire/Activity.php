<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Repositories\Contratcs\ActivityRepositoryInterface;
use App\Repositories\Contratcs\CategoryRepositoryInterface;

class Activity extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $title = '';

    public string $category = '';

    public string $sort_by = 'popular';

    public function mount()
    {
        $this->category = $this->categoryRepository->first()?->slug ?? '';
    }

    #[Computed()]
    public function activityRepository()
    {
        return app(ActivityRepositoryInterface::class);
    }

    #[Computed()]
    public function categoryRepository()
    {
        return app(CategoryRepositoryInterface::class);
    }

    public function resetInput()
    {
        $this->title = '';
        $this->sort_by = 'popular';
        $this->category = $this->categoryRepository->first()?->slug ?? '';

        $this->resetPage();
    }

    public function render()
    {
        $activities = $this->activityRepository->findByFilters(
            $this->title,
            $this->category,
            $this->sort_by
        );

        $categories = $this->categoryRepository->all();

        return view(
            'livewire.activity',
            compact('activities', 'categories')
        );
    }
}
