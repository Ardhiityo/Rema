<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Repositories\Contratcs\StudyProgramRepositoryInterface;

class StudyProgramList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $keyword = '';

    public function resetInput()
    {
        $this->keyword = '';
        $this->resetPage();
    }

    #[On('refresh-study-programs')]
    public function getStudyProgramsProperty(StudyProgramRepositoryInterface $studyProgramRepository)
    {
        return $studyProgramRepository->findByFilters($this->keyword);
    }

    public function render()
    {
        return view('livewire.study-program-list');
    }
}
