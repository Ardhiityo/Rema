<?php

namespace App\Livewire\Author;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Repositories\Contratcs\AuthorRepositoryInterface;
use App\Repositories\Contratcs\StudyProgramRepositoryInterface;
use Livewire\Attributes\Computed;

class AuthorList extends Component
{
    use WithPagination;

    public string $study_program_slug = '';
    public string $keyword = '';

    #[On('refresh-authors')]
    public function getAuthorsProperty(AuthorRepositoryInterface $authorRepository)
    {
        return $authorRepository->findByFilters(
            $this->keyword,
            $this->study_program_slug
        );
    }

    public function resetInput()
    {
        $this->keyword = '';
        $this->study_program_slug = '';

        $this->resetPage();
    }

    #[Computed()]
    public function studyProgramRepository()
    {
        return app(StudyProgramRepositoryInterface::class);
    }

    public function render()
    {
        $study_programs = $this->studyProgramRepository()->all();

        return view('livewire.author.list', compact('study_programs'));
    }
}
