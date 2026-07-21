<?php

namespace App\Livewire\Faculty;

use App\Data\Faculty\CreateFacultyData;
use App\Data\Faculty\UpdateFacultyData;
use App\Repositories\Contratcs\FacultyRepositoryInterface;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class FacultyForm extends Component
{
    // Start Form
    public string $name = '';

    public string $slug = '';
    // End Form

    public int $faculty_id;

    public bool $is_update = false;

    #[Computed()]
    public function formTitle()
    {
        return $this->is_update ? 'Edit Faculty' : 'Create New Faculty';
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string'],
            'slug' => ['required', 'string', 'min:3', 'max:50', $this->is_update ? 'unique:faculties,slug,'.$this->faculty_id : 'unique:faculties,slug'],
        ];
    }

    protected function validationAttributes()
    {
        return [
            'slug' => 'faculty',
        ];
    }

    #[Computed()]
    public function facultyRepository()
    {
        return app(FacultyRepositoryInterface::class);
    }

    public function create()
    {
        $this->slug = Str::slug($this->name);

        $validated = $this->validate();

        try {
            $create_faculty_data = CreateFacultyData::from($validated);

            $this->facultyRepository->create($create_faculty_data);

            $this->resetInput();

            $this->dispatch('refresh-faculties');

            session()->flash('faculty-success', 'The faculty was successfully created.');
        } catch (Throwable $th) {
            logger()->error($th->getMessage(), ['FacultyForm' => 'create']);
            session()->flash('faculty-failed', 'Failed creating faculty');
        }
    }

    #[On('faculty-edit')]
    public function edit($faculty_id)
    {
        try {
            $faculty_data = $this->facultyRepository->findById($faculty_id);

            $this->faculty_id = $faculty_data->id;

            $this->name = $faculty_data->name;

            $this->is_update = true;
        } catch (Throwable $th) {
            logger()->error($th->getMessage(), ['FacultyForm' => 'edit']);
            session()->flash('faculty-failed', 'Failed editing faculty');
        }
    }

    public function update()
    {
        try {
            $this->slug = Str::slug($this->name);

            $validated = $this->validate();

            $update_faculty_data = UpdateFacultyData::from($validated);

            $this->facultyRepository->update(
                $this->faculty_id,
                $update_faculty_data
            );

            $this->resetInput();

            $this->dispatch('refresh-faculties');

            session()->flash('faculty-success', 'The faculty was successfully updated.');
        } catch (Throwable $th) {
            logger()->error($th->getMessage(), ['FacultyForm' => 'update']);
            session()->flash('faculty-failed', 'Failed updating faculty');
        }
    }

    #[On('faculty-delete-confirm')]
    public function deleteConfirm($faculty_id)
    {
        try {
            $faculty_data = $this->facultyRepository->findById($faculty_id);

            $this->faculty_id = $faculty_data->id;
        } catch (Throwable $th) {
            logger()->error($th->getMessage(), ['FacultyForm' => 'deleteConfirm']);
            session()->flash('faculty-failed', 'Failed deleting faculty');
        }
    }

    #[On('faculty-delete')]
    public function delete()
    {
        try {
            $this->facultyRepository->delete($this->faculty_id);

            $this->dispatch('refresh-faculties');

            $this->resetInput();

            session()->flash('faculty-success', 'The faculty was successfully deleted.');
        } catch (Throwable $th) {
            logger()->error($th->getMessage(), ['FacultyForm' => 'delete']);
            session()->flash('faculty-failed', 'Failed deleting faculty');
        }
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
        return view('livewire.faculty.form');
    }
}
