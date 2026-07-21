<?php

namespace App\Livewire\Staff;

use App\Data\Staff\CreateStaffData;
use App\Data\Staff\UpdateStaffData;
use App\Data\User\CreateUserData;
use App\Data\User\UpdateUserData;
use App\Repositories\Contratcs\FacultyRepositoryInterface;
use App\Repositories\Contratcs\StaffRepositoryInterface;
use App\Repositories\Contratcs\UserRepositoryInterface;
use App\Rules\UpdateUserAvatarRule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class StaffForm extends Component
{
    use WithFileUploads;

    // Form Start
    public string $email = '';

    public string $password = '';

    public string $name = '';

    public int|string|null $faculty_id = '';

    public $avatar = null;
    // Form End

    public string|bool $display_avatar = false;

    public ?int $staff_id = null;

    public ?int $user_id = null;

    #[Computed()]
    public bool $is_update = false;

    #[Computed()]
    public function formTitle()
    {
        return $this->is_update ? 'Edit Staff' : 'Create New Staff';
    }

    public function updatedAvatar()
    {
        $this->display_avatar = false;
    }

    protected function rulesCreate(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'faculty_id' => ['required', 'exists:faculties,id'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:1000'],
            'email' => ['required', 'email:dns', 'unique:users,email'],
            'password' => ['required', 'min:8', 'max:50', Password::min(8)->letters()->numbers()->mixedCase()->symbols()],
        ];
    }

    public function rulesUpdate(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:50'],
            'faculty_id' => ['required', 'exists:faculties,id'],
            'avatar' => [new UpdateUserAvatarRule(user_id: $this->user_id, max_KB: 1000, allowedMimes: ['jpg', 'jpeg', 'png'])],
            'email' => ['required', 'email:dns', 'unique:users,email,'.$this->user_id],
            'password' => ['nullable', 'min:8', 'max:50', Password::min(8)->letters()->numbers()->mixedCase()->symbols()],
        ];
    }

    protected function validationAttributes()
    {
        return [
            'faculty_id' => 'faculty',
        ];
    }

    #[Computed()]
    public function userRepository()
    {
        return app(UserRepositoryInterface::class);
    }

    #[Computed()]
    public function staffRepository()
    {
        return app(StaffRepositoryInterface::class);
    }

    public function create()
    {
        $validated = $this->validate($this->rulesCreate());

        try {
            $create_user_data = CreateUserData::from($validated);

            $user_data = $this->userRepository->create($create_user_data);

            $validated['user_id'] = $user_data->id;

            $create_staff_data = CreateStaffData::from($validated);

            $this->staffRepository->create($create_staff_data);

            $this->resetInput();

            $this->dispatch('refresh-staff');

            session()->flash('staff-success', 'The staff was successfully created.');
        } catch (Throwable $th) {
            logger()->error($th->getMessage(), ['StaffForm' => 'create']);
            session()->flash('staff-failed', 'Failed creating staff');
        }
    }

    #[On('staff-edit')]
    public function edit($staff_id)
    {
        try {
            $staff_data = $this->staffRepository->findById($staff_id);
            $this->staff_id = $staff_data->id;
            $this->faculty_id = $staff_data->faculty_id;

            $user_data = $this->userRepository->findById($staff_data->user_id);
            $this->user_id = $user_data->id;
            $this->name = $user_data->name;
            $this->email = $user_data->email;
            $this->display_avatar = $user_data->avatar;

            $this->is_update = true;
        } catch (Throwable $th) {
            logger()->error($th->getMessage(), ['StaffForm' => 'edit']);
            session()->flash('staff-failed', 'Failed editing staff');
        }
    }

    public function update()
    {
        $validated = $this->validate($this->rulesUpdate());

        try {
            $update_user_data = UpdateUserData::from($validated);

            $this->userRepository->update($this->user_id, $update_user_data);

            $update_staff_data = UpdateStaffData::from($validated);

            $this->staffRepository->update($this->staff_id, $update_staff_data);

            $this->dispatch('refresh-staff');

            $this->is_update = false;

            $this->display_avatar = false;

            $this->resetInput();

            session()->flash('staff-success', 'The staff was successfully updated.');
        } catch (Throwable $th) {
            logger()->error($th->getMessage(), ['StaffForm' => 'update']);
            session()->flash('staff-failed', 'Failed updating staff');
        }
    }

    #[On('staff-delete-confirm')]
    public function deleteConfirm($staff_id)
    {
        try {
            $staff_data = $this->staffRepository->findById($staff_id);
            $this->staff_id = $staff_data->id;
            $this->user_id = $staff_data->user_id;
        } catch (Throwable $th) {
            logger()->error($th->getMessage(), ['StaffForm' => 'deleteConfirm']);
            session()->flash('staff-failed', 'Failed deleting staff');
        }
    }

    #[On('staff-delete')]
    public function delete()
    {
        try {
            $this->userRepository->delete($this->user_id);

            $this->dispatch('refresh-staff');

            $this->resetInput();

            $this->display_avatar = false;

            session()->flash('staff-success', 'The staff was successfully deleted.');
        } catch (Throwable $th) {
            logger()->error($th->getMessage(), ['StaffForm' => 'delete']);
            session()->flash('staff-failed', 'Failed deleting staff');
        }
    }

    public function resetInput()
    {
        $this->name = '';
        $this->faculty_id = '';
        $this->email = '';
        $this->password = '';
        $this->avatar = '';
        $this->display_avatar = false;

        if ($this->is_update) {
            $this->is_update = false;
        }

        $this->resetErrorBag();
    }

    public function render(FacultyRepositoryInterface $facultyRepositoryInterface)
    {
        $faculties = $facultyRepositoryInterface->all();

        return view('livewire.staff.form', compact('faculties'));
    }
}
