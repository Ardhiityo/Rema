<?php

namespace App\Livewire;

use App\Data\StudyProgramData;
use App\Models\StudyProgram;
use Livewire\Component;
use Livewire\WithPagination;

class StudyProgramList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $keyword = '';

    public function render()
    {
        $query = StudyProgram::query();

        if ($keyword = $this->keyword) {
            $query->whereLike('name', "%$keyword%");
        }

        $study_programs = StudyProgramData::collect($query->paginate(5));

        return view('livewire.study-program-list', compact('study_programs'));
    }
}
