<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\StudyProgram;
use Livewire\WithPagination;
use  App\Data\StudyProgram\StudyProgramData;

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
