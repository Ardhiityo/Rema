<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Data\StudyProgramData;
use Livewire\Attributes\Computed;

class StudyProgram extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $keyword = '';
    public string $name = '';
    public string $slug = '';
    public int|null $study_program_id = null;
    public bool $is_update = false;

    #[Computed()]
    public function formTitle()
    {
        return $this->is_update ? 'Edit Study Program' : 'Create New Study Program';
    }

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
            'slug' => 'study program'
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
        $this->study_program_id = $study_program->id;

        $this->isUpdate = true;
    }

    public function update()
    {
        $validated = $this->validate();

        $study_program = \App\Models\StudyProgram::find($this->study_program_id);

        $study_program->update($validated);

        $this->isUpdate = false;

        $this->resetInput();
    }

    public function deleteConfirm($study_program_slug)
    {
        $study_program = \App\Models\StudyProgram::where('slug', $study_program_slug)->first();

        $this->study_program_id = $study_program->id;
        $this->name = $study_program->name;
    }

    public function delete()
    {
        \App\Models\StudyProgram::find($this->study_program_id)->delete();
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

        $study_programs = StudyProgramData::collect(
            $query->orderByDesc('id')->paginate(10)
        );

        return view('livewire.study-program', compact('study_programs'));
    }
}
