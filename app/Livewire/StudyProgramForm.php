<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use App\Data\StudyProgram\CreateStudyProgramData;
use App\Data\StudyProgram\UpdateStudyProgramData;
use App\Repositories\Contratcs\StudyProgramRepositoryInterface;

class StudyProgramForm extends Component
{
    // Start Form
    public string $name = '';
    public string $slug = '';
    // End Form

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

    #[Computed()]
    public function studyProgramRepository()
    {
        return app(StudyProgramRepositoryInterface::class);
    }

    public function create()
    {
        $this->slug = Str::slug($this->name);

        $validated = $this->validate();

        $create_study_program_data = CreateStudyProgramData::from($validated);

        $this->studyProgramRepository->create($create_study_program_data);

        $this->resetInput();

        $this->dispatch('refresh-study-programs');

        session()->flash('message', 'The study program was successfully created.');
    }

    #[On('study-program-edit')]
    public function edit($study_program_id)
    {
        $study_program_data = $this->studyProgramRepository->findById($study_program_id);

        $this->study_program_id = $study_program_data->id;

        $this->name = $study_program_data->name;

        $this->is_update = true;
    }

    public function update()
    {
        $this->slug = Str::slug($this->name);

        $validated = $this->validate();

        $update_study_program_data = UpdateStudyProgramData::from($validated);

        $this->studyProgramRepository->update($this->study_program_id, $update_study_program_data);

        $this->resetInput();

        $this->dispatch('refresh-study-programs');

        session()->flash('message', 'The study program was successfully updated.');
    }

    #[On('study-program-delete-confirm')]
    public function deleteConfirm($study_program_id)
    {
        $study_program_data = $this->studyProgramRepository->findById($study_program_id);

        $this->study_program_id = $study_program_data->id;

        $this->name = $study_program_data->name;
    }

    #[On('study-program-delete')]
    public function delete()
    {
        $this->studyProgramRepository->delete($this->study_program_id);

        $this->dispatch('refresh-study-programs');

        $this->resetInput();

        session()->flash('message', 'The study program was successfully deleted.');
    }

    public function resetInput()
    {
        $this->name = '';
        $this->slug = '';

        if ($this->is_update) {
            $this->is_update = false;
        }

        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.study-program-form');
    }
}
