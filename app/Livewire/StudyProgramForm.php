<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\StudyProgram;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class StudyProgramForm extends Component
{
    public string $name = '';
    public string $slug = '';
    public int $study_program_id;
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
            'slug' => ['required', 'min:3', 'max:50', $this->is_update ? 'unique:study_programs,slug,' . $this->study_program_id : 'unique:study_programs,slug']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'slug' => 'study program'
        ];
    }

    public function create()
    {
        $this->slug = Str::slug($this->name);

        $validated = $this->validate();

        StudyProgram::create($validated);

        $this->resetInput();

        $this->dispatch('refresh-study-programs');

        return session()->flash('message', 'The study program was successfully created.');
    }

    #[On('study-program-edit')]
    public function edit($study_program_id)
    {
        $study_program = StudyProgram::find($study_program_id);

        $this->name = $study_program->name;
        $this->slug = $study_program->slug;
        $this->study_program_id = $study_program->id;

        $this->is_update = true;
    }

    public function update()
    {
        $validated = $this->validate();

        $study_program = StudyProgram::find($this->study_program_id);

        $study_program->update($validated);

        $this->is_update = false;

        $this->resetInput();

        $this->dispatch('refresh-study-programs');

        return session()->flash('message', 'The study program was successfully updated.');
    }

    #[On('study-program-delete-confirm')]
    public function deleteConfirm($study_program_id)
    {
        $study_program = StudyProgram::find($study_program_id);

        $this->study_program_id = $study_program->id;
        $this->name = $study_program->name;
    }

    #[On('study-program-delete')]
    public function delete()
    {
        StudyProgram::find($this->study_program_id)->delete();

        $this->dispatch('refresh-study-programs');

        return session()->flash('message', 'The study program was successfully deleted.');
    }

    public function resetInput()
    {
        $this->name = '';
        $this->slug = '';

        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.study-program-form');
    }
}
