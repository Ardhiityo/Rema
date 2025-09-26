<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudyProgram;

class StudyProgramForm extends Component
{
    public string $name = '';
    public string $slug = '';

    protected function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:50', 'unique:study_programs,name'],
        ];
    }

    protected function validationAttributes()
    {
        return [
            'name' => 'Study Program',
        ];
    }


    public function updatedName($value) {}

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
