<?php

namespace App\Livewire;

use Throwable;
use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Data\Coordinator\CreateCoordinatorData;
use App\Data\Coordinator\UpdateCoordinatorData;
use App\Repositories\Contratcs\CoordinatorRepositoryInterface;
use App\Repositories\Contratcs\StudyProgramRepositoryInterface;
use Livewire\Attributes\On;

class CoordinatorForm extends Component
{
    // Form Start
    public string|int $nidn = '';
    public string|int $study_program_id = '';
    public string $name = '';
    public string $position = '';
    // Form End

    public string|int $coordinator_id = '';

    #[Computed()]
    public bool $is_update = false;

    #[Computed()]
    public function formTitle()
    {
        return $this->is_update ? 'Edit Coordinator' : 'Create New Coordinator';
    }

    public function rules()
    {
        return [
            'name' => ['required', 'min:3'],
            'position' => ['required', 'min:3'],
            'nidn' => ['required', 'numeric', $this->is_update ? 'unique:coordinators,nidn,' . $this->coordinator_id : 'unique:coordinators,nidn'],
            'study_program_id' => ['required', 'exists:study_programs,id', $this->is_update ? 'unique:coordinators,study_program_id,' . $this->coordinator_id : 'unique:coordinators,study_program_id']
        ];
    }

    #[Computed()]
    public function coordinatorRepository()
    {
        return app(CoordinatorRepositoryInterface::class);
    }

    #[Computed()]
    public function studyProgramRepository()
    {
        return app(StudyProgramRepositoryInterface::class);
    }

    protected function validationAttributes()
    {
        return [
            'study_program_id' => 'study program'
        ];
    }

    public function create()
    {
        $validated = $this->validate();

        try {
            $create_coordinator_data = CreateCoordinatorData::from($validated);

            $this->coordinatorRepository->create($create_coordinator_data);

            $this->resetInput();

            $this->dispatch('refresh-coordinators');

            session()->flash('coordinator-success', 'The coordinator was successfully created.');
        } catch (Throwable $th) {
            session()->flash('coordinator-failed', $th->getMessage());
        }
    }

    #[On('coordinator-edit')]
    public function edit(int $coordinator_id)
    {
        try {
            $coordinator_data = $this->coordinatorRepository->findById($coordinator_id);
            $this->coordinator_id = $coordinator_data->id;
            $this->nidn = $coordinator_data->nidn;
            $this->name = $coordinator_data->name;
            $this->position = $coordinator_data->position;
            $this->study_program_id = $coordinator_data->study_program_id;
            $this->is_update = true;
        } catch (Throwable $th) {
            session()->flash('coordinator-failed', $th->getMessage());
        }
    }

    public function update()
    {
        $validated = $this->validate();

        try {
            $update_coordinator_data = UpdateCoordinatorData::from($validated);

            $this->coordinatorRepository->update(
                $this->coordinator_id,
                $update_coordinator_data
            );

            $this->resetInput();

            $this->dispatch('refresh-coordinators');

            session()->flash('coordinator-success', 'The coordinator was successfully updated.');
        } catch (Throwable $th) {
            session()->flash('coordinator-failed', $th->getMessage());
        }
    }

    #[On('coordinator-delete-confirm')]
    public function deleteConfirm(int $coordinator_id)
    {
        try {
            $coordinator_data = $this->coordinatorRepository->findById($coordinator_id);

            $this->coordinator_id = $coordinator_data->id;
        } catch (Throwable $th) {
            session()->flash('coordinator-failed', $th->getMessage());
        }
    }

    #[On('coordinator-delete')]
    public function delete()
    {
        try {
            $this->coordinatorRepository->delete($this->coordinator_id);

            $this->resetInput();

            $this->dispatch('refresh-coordinators');

            session()->flash('coordinator-success', 'The coordinator was successfully deleted.');
        } catch (Throwable $th) {
            session()->flash('coordinator-failed', $th->getMessage());
        }
    }

    public function resetInput()
    {
        $this->name = '';
        $this->position = '';
        $this->nidn = '';
        $this->study_program_id = '';

        if ($this->is_update) {
            $this->is_update = false;
        }

        $this->resetErrorBag();
    }

    public function render()
    {
        $study_programs = $this->studyProgramRepository->all();

        return view('livewire.coordinator-form', compact('study_programs'));
    }
}
