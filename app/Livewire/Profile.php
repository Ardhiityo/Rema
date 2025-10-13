<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Author;
use Livewire\Component;
use App\Data\User\UserData;
use App\Models\StudyProgram;
use App\Rules\ProfileNimRule;
use Livewire\WithFileUploads;
use App\Data\Author\AuthorData;
use Illuminate\Validation\Rule;
use App\Rules\ProfileAvatarRule;
use App\Data\User\UpdateUserData;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Data\Author\UpdateAuthorData;
use App\Rules\ProfileStudyProgramRule;
use App\Data\StudyProgram\StudyProgramData;
use App\Repositories\Contratcs\UserRepositoryInterface;
use App\Repositories\Contratcs\AuthorRepositoryInterface;

class Profile extends Component
{
    use WithFileUploads;

    // Start Form
    public string $name = '';
    public int|null|string $nim = null;
    public int|null|string $study_program_id = null;
    public string $password = '';
    public $avatar;
    // End Form

    public string $email = '';
    public string|bool|null $display_avatar = '';

    public User $user;
    public Author $author;
    public string $status;
    public string $role = '';

    public function mount()
    {
        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            $user->load('author');
            $this->author = $user->author;

            $author_data = AuthorData::fromModel($user->author);
            $this->role = $user->getRoleNames()->first();
            $this->nim = $author_data->nim;
            $this->status = $author_data->status;
            $this->study_program_id = $author_data->study_program_id;
        }

        $this->user = $user;
        $user_data = UserData::fromModel($user);
        $this->name = $user_data->name;
        $this->email = $user_data->email;
        $this->display_avatar = $user_data->avatar;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:50'],
            'nim' => ['required_if:role,contributor', new ProfileNimRule()],
            'study_program_id' => ['required_if:role,contributor', new ProfileStudyProgramRule()],
            'avatar' => [Rule::requiredIf($this->user->avatar == null), new ProfileAvatarRule()],
            'password' => ['nullable', 'min:8', 'max:100'],
        ];
    }

    public function updatedAvatar()
    {
        $this->display_avatar = false;
    }

    #[Computed()]
    public function isLockForm()
    {
        if ($this->user->hasRole('contributor')) {
            return $this->status == 'approve' ? true : false;
        }
    }

    public function resetInput()
    {
        $this->name = '';
        $this->nim = '';
        $this->study_program_id = '';
        $this->password = '';

        $this->resetErrorBag();
    }

    public function getUserRepositoryProperty(UserRepositoryInterface $userRepository)
    {
        return $userRepository;
    }

    public function getAuthorRepositoryProperty(AuthorRepositoryInterface $authorRepository)
    {
        return $authorRepository;
    }

    public function update()
    {
        $user_data = UserData::fromModel($this->user);

        if ($this->user->hasRole('contributor')) {
            $author_data = AuthorData::fromModel($this->author);
        }

        if ($this->isLockForm()) {
            $this->name = $user_data->name;
            $this->nim = $author_data->nim;
            $this->study_program_id = $author_data->study_program_id;
            $this->avatar = null;
        }

        $validated = $this->validate();

        if ($this->user->hasRole('contributor')) {
            $validated['status'] = $this->status;

            $update_author_data = UpdateAuthorData::from($validated);

            $this->author_repository->update($author_data->id, $update_author_data);
        }

        $validated['email'] = $user_data->email;

        $update_user_data = UpdateUserData::from($validated);

        $this->user_repository->update($user_data->id, $update_user_data);

        session()->flash('message', 'The profile was successfully updated.');
    }

    public function render()
    {
        $study_programs = [];

        if ($this->user->hasRole('contributor')) {
            $study_programs = StudyProgramData::collect(StudyProgram::get());
        }

        return view('livewire.profile', compact('study_programs'));
    }
}
