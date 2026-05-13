<?php

namespace App\Livewire\Staff;

use App\Repositories\Contratcs\FacultyRepositoryInterface;
use App\Repositories\Contratcs\StaffRepositoryInterface;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class StaffList extends Component
{
    use WithPagination;

    public string $faculty_slug = '';
    public string $keyword = '';

    #[On('refresh-staff')]
    public function getStaffsProperty(StaffRepositoryInterface $staffRepository)
    {
        return $staffRepository->findByFilters(
            $this->keyword,
            $this->faculty_slug
        );
    }

    public function resetInput()
    {
        $this->keyword = '';
        $this->faculty_slug = '';

        $this->resetPage();
    }

    public function render(FacultyRepositoryInterface $facultyRepositoryInterface)
    {
        $faculties = $facultyRepositoryInterface->all();

        return view('livewire.staff.list', compact('faculties'));
    }
}
