<?php

namespace App\Livewire\Faculty;

use App\Repositories\Contratcs\FacultyRepositoryInterface;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class FacultyList extends Component
{
    use WithPagination;

    public string $keyword = '';

    public function resetInput()
    {
        $this->keyword = '';
        $this->resetPage();
    }

    #[On('refresh-faculties')]
    public function getFacultiesProperty(FacultyRepositoryInterface $facultyRepository)
    {
        return $facultyRepository->findByFilters($this->keyword);
    }
    
    public function render()
    {
        return view('livewire.faculty.list');
    }
}
