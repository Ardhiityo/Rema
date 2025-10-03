<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudyProgram;
use App\Data\StudyProgramData;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class StudyProgramList extends Component
{
    use WithPagination;

    public string $keyword = '';

    public function resetInput()
    {
        $this->keyword = '';
        $this->resetPage();
    }

    #[On('refresh-study-programs')]
    public function getStudyProgramsProperty()
    {
        $query = StudyProgram::query();

        if ($keyword = $this->keyword) {
            $query->whereLike('name', "%$keyword%");
        }

        return StudyProgramData::collect(
            $query->orderByDesc('id')->paginate(10)
        );
    }

    public function render()
    {
        return view('livewire.study-program-list');
    }
}
