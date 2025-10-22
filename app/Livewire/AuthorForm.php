<?php

namespace App\Livewire;

use Throwable;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Data\User\CreateUserData;
use App\Data\User\UpdateUserData;
use Livewire\Attributes\Computed;
use App\Rules\UpdateUserAvatarRule;
use App\Data\Author\CreateAuthorData;
use App\Data\Author\UpdateAuthorData;
use App\Repositories\Contratcs\UserRepositoryInterface;
use App\Repositories\Contratcs\AuthorRepositoryInterface;
use App\Repositories\Contratcs\StudyProgramRepositoryInterface;

class AuthorForm extends Component
{
    use WithFileUploads;

    // Form Start
    public int|string|null $nim = '';
    public string $email = '';
    public string $password = '';
    public string $name = '';
    public int|string|null $study_program_id = '';
    public string $status = '';
    public $avatar = null;
    // Form End

    public string|bool $display_avatar = false;
    public int|null $author_id = null;
    public int|null $user_id = null;
    #[Computed()]
    public bool $is_update = false;

    #[Computed()]
    public function formTitle()
    {
        return $this->is_update ? 'Edit Author' : 'Create New Author';
    }

    public function updatedAvatar()
    {
        $this->display_avatar = false;
    }

    protected function rulesCreate(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:50'],
            'nim' => ['required', 'numeric', 'digits_between:8,15', 'unique:authors,nim'],
            'study_program_id' => ['required', 'exists:study_programs,id'],
            'avatar' => ['nullable', 'file', 'mimes:jpg,png', 'max:1000'],
            'email' => ['nullable', 'email:dns', 'unique:users,email'],
            'password' =>  ['nullable', 'min:8', 'max:50'],
            'status' => ['required', 'in:approve,reject,pending']
        ];
    }

    public function rulesUpdate(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:50'],
            'nim' => ['required', 'numeric', 'digits_between:8,15', 'unique:authors,nim,' . $this->author_id],
            'study_program_id' => ['required', 'exists:study_programs,id'],
            'avatar' => [new UpdateUserAvatarRule(user_id: $this->user_id, max_KB: 1000, allowedMimes: ['jpg', 'png'])],
            'email' => ['required', 'email:dns', 'unique:users,email,' . $this->user_id],
            'password' => ['nullable', 'min:8', 'max:50'],
            'status' => ['required', 'in:approve,reject,pending']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'study_program_id' => 'study program'
        ];
    }

    #[Computed()]
    public function userRepository()
    {
        return app(UserRepositoryInterface::class);
    }

    #[Computed()]
    public function authorRepository()
    {
        return app(AuthorRepositoryInterface::class);
    }

    public function create()
    {
        try {
            $validated = $this->validate($this->rulesCreate());

            $create_user_data = CreateUserData::from($validated);

            $user_data = $this->userRepository->create($create_user_data);

            $validated['user_id'] = $user_data->id;

            $create_author_data = CreateAuthorData::from($validated);

            $this->authorRepository->create($create_author_data);

            $this->resetInput();

            $this->dispatch('refresh-authors');

            session()->flash('author-success', 'The author was successfully created.');
        } catch (Throwable $th) {
            session()->flash('author-failed', $th->getMessage());
        }
    }

    #[On('author-edit')]
    public function edit($author_id)
    {
        try {
            $author_data = $this->authorRepository->findById($author_id);
            $this->author_id = $author_data->id;
            $this->nim = $author_data->nim;
            $this->study_program_id = $author_data->study_program_id;
            $this->status = $author_data->status;

            $user_data = $this->userRepository->findById($author_data->user_id);
            $this->user_id = $user_data->id;
            $this->name = $user_data->name;
            $this->email = $user_data->email;
            $this->display_avatar = $user_data->avatar;

            $this->is_update = true;
        } catch (Throwable $th) {
            session()->flash('author-failed', $th->getMessage());
        }
    }

    public function update()
    {
        try {
            $validated = $this->validate($this->rulesUpdate());

            $update_user_data = UpdateUserData::from($validated);

            $this->userRepository->update($this->user_id, $update_user_data);

            $update_author_data = UpdateAuthorData::from($validated);

            $this->authorRepository->update($this->author_id, $update_author_data);

            $this->dispatch('refresh-authors');

            $this->is_update = false;

            $this->display_avatar = false;

            $this->resetInput();

            session()->flash('author-success', 'The author was successfully updated.');
        } catch (Throwable $th) {
            session()->flash('author-failed', $th->getMessage());
        }
    }

    #[On('author-delete-confirm')]
    public function deleteConfirm($author_id)
    {
        try {
            $author_data = $this->authorRepository->findById($author_id);

            $this->user_id = $author_data->user_id;
        } catch (Throwable $th) {
            session()->flash('author-failed', $th->getMessage());
        }
    }

    #[On('author-delete')]
    public function delete()
    {
        try {
            $this->userRepository->delete($this->user_id);

            $this->dispatch('refresh-authors');

            $this->resetInput();

            $this->display_avatar = false;

            session()->flash('author-success', 'The author was successfully deleted.');
        } catch (Throwable $th) {
            session()->flash('author-failed', $th->getMessage());
        }
    }

    public function resetInput()
    {
        $this->name = '';
        $this->nim = '';
        $this->study_program_id = '';
        $this->email = '';
        $this->password = '';
        $this->status = '';
        $this->avatar = '';
        $this->display_avatar = false;

        if ($this->is_update) {
            $this->is_update = false;
        }

        $this->resetErrorBag();
    }

    public function render(StudyProgramRepositoryInterface $studyProgramRepository)
    {
        $study_programs = $studyProgramRepository->all();

        return view('livewire.author-form', compact('study_programs'));
    }
}
