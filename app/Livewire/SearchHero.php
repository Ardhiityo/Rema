<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Repositories\Contratcs\CategoryRepositoryInterface;
use App\Repositories\Contratcs\LandingPageRepositoryInterface;

class SearchHero extends Component
{
    use WithPagination;

    public string $title = '';
    public string $category = '';
    public string $year = '';
    public string $author = '';

    protected function queryString()
    {
        return [
            'category' => [
                'except' => ''
            ],
            'title' => [
                'except' => ''
            ],
            'year' => [
                'except' => ''
            ],
            'author' => [
                'except' => ''
            ]
        ];
    }

    public function mount()
    {
        $this->category = $this->categoryRepository->first()?->slug ?? '';
    }

    public function resetInput()
    {
        $this->title = '';
        $this->year = '';
        $this->author = '';
        $this->category = $this->categoryRepository->first()?->slug ?? '';

        $this->resetPage();
    }

    public function updatedPage()
    {
        $this->dispatch('scroll-to-search');
    }

    #[Computed()]
    public function categoryRepository()
    {
        return app(CategoryRepositoryInterface::class);
    }

    #[Layout('layouts.welcome')]
    public function render(LandingPageRepositoryInterface $landingPageRepository)
    {
        $repositories = $landingPageRepository->searchHero(
            $this->title,
            $this->year,
            $this->author,
            $this->category
        );

        $categories = $this->categoryRepository->all();

        return view('livewire.search-hero', compact('repositories', 'categories'));
    }
}
