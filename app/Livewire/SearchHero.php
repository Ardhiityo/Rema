<?php

namespace App\Livewire;

use App\Repositories\Contratcs\CategoryRepositoryInterface;
use App\Repositories\Contratcs\LandingPageRepositoryInterface;
use App\Repositories\Contratcs\StudyProgramRepositoryInterface;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class SearchHero extends Component
{
    use WithPagination;

    public string $title = '';
    public string $category = '';
    public string $year = '';
    public string $author = '';
    public string $study_program = '';

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
            ],
            'study_program' => [
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
        $this->study_program = '';
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

    #[Computed()]
    public function studyProgramRepository()
    {
        return app(StudyProgramRepositoryInterface::class);
    }

    public function updated($property, $value)
    {
        if (trim($value)) {
            $this->resetPage();
        }
    }

    #[Layout('layouts.welcome')]
    public function render(LandingPageRepositoryInterface $landingPageRepository)
    {
        $repositories = $landingPageRepository->searchHero(
            trim($this->title),
            trim($this->year),
            trim($this->author),
            trim($this->category),
            trim($this->study_program)
        );

        $categories = $this->categoryRepository->all();
        $study_programs = $this->studyProgramRepository->all();

        return view('livewire.search-hero', compact('repositories', 'categories', 'study_programs'));
    }
}
