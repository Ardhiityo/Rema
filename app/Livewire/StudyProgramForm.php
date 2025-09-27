<?php

namespace App\Livewire;

use App\Data\StudyProgramData;
use Livewire\Component;
use App\Models\StudyProgram;

class StudyProgramForm extends Component
{
    public string $name = '';
    public string $slug = '';

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

    public function createStudyProgram()
    {
        $validated = $this->validate();

        StudyProgram::create($validated);

        return redirect()->route('study-program.index');
    }

    public function render()
    {
        return view('livewire.study-program-form');
    }
}
