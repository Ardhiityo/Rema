<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Data\StudyProgramData;
use Illuminate\Support\Facades\Log;

class StudyProgram extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $keyword = '';
    public string $name = '';
    public string $slug = '';
    public bool $isUpdate = false;

    protected function rules()
    {
        return [
            'name' => ['required'],
            'slug' => ['required', 'min:3', 'max:50', 'unique:study_programs,slug']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'slug' => 'Study Program'
        ];
    }

    public function updatedName($value)
    {
        $study_program = StudyProgramData::from([
            'name' => $value,
        ]);

        $this->slug = $study_program->getSlug();
    }

    public function create()
    {
        $validated = $this->validate();

        \App\Models\StudyProgram::create($validated);

        return redirect()->route('study-program.index');
    }

    public function edit($study_program_slug)
    {
        $study_program = \App\Models\StudyProgram::where('slug', $study_program_slug)->first();

        $this->name = $study_program->name;
        $this->slug = $study_program->slug;

        $this->isUpdate = true;
    }

    public function update()
    {
        $study_program = \App\Models\StudyProgram::where('slug', $this->slug)->first();

        $validated = $this->validate();

        $study_program->update($validated);

        $this->isUpdate = false;
    }

    public function resetInput()
    {
        $this->reset();
        $this->resetPage();
        $this->resetErrorBag();
    }

    public function render()
    {
        $query = \App\Models\StudyProgram::query();

        if ($keyword = $this->keyword) {
            $query->whereLike('name', "%$keyword%");
        }

        $study_programs = StudyProgramData::collect($query->paginate(5));

        return view('livewire.study-program', compact('study_programs'));
    }
}
