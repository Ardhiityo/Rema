<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudyProgram;
use Livewire\WithFileUploads;
use App\Data\StudyProgramData;
use App\Data\UserData;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;

class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string|null $nim = '';
    public int|null|string $study_program_id = null;
    public string $email = '';
    public string $password = '';
    public $avatar;
    public string|bool|null $display_avatar = '';
    public int|null $user_id = null;
    public User $user;

    public function mount()
    {
        $user = Auth::user();
        $this->user = $user;

        $user_data = UserData::fromModel($user);
        $this->user_id = $user_data->id;
        $this->name = $user_data->name;
        $this->email = $user_data->email;
        $this->display_avatar = $user->avatar;

        $user = $user->load('author');
        $this->nim = $user->author?->nim;
        $this->study_program_id = $user->author?->study_program_id;
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:50'],
            'nim' => $this->nimRule(),
            'study_program_id' => $this->studyProgramRule(),
            'password' => ['nullable', 'min:8', 'max:100'],
            'avatar' => $this->user->avatar ?
                [
                    'nullable',
                    'file',
                    'mimes:jpg,png',
                    'max:1000'
                ]
                : [
                    'required',
                    'file',
                    'mimes:jpg,png',
                    'max:1000'
                ]
        ];
    }

    public function updatedAvatar()
    {
        $this->display_avatar = false;
    }

    protected function validationAttributes()
    {
        return [
            'study_program_id' => 'study program',
        ];
    }

    #[Computed()]
    public function isLockForm()
    {
        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            return $user->author->status == 'approve' ? true : false;
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

    public function nimRule()
    {
        $user = Auth::user();

        return $user->hasRole('contributor') ? ['required', 'unique:authors,nim,' . $user->author->id, 'min:8', 'max:50'] : ['nullable'];
    }

    public function studyProgramRule()
    {
        $user = Auth::user();

        return $user->hasRole('contributor') ? ['required', 'exists:study_programs,id'] : ['nullable'];
    }

    public function update()
    {
        $user = Auth::user();

        if ($this->isLockForm()) {
            $this->name = $user->name;
            $this->nim = $user->author->nim;
            $this->study_program_id = $user->author->study_program_id;
            $this->avatar = null;
        }

        $validated = $this->validate();

        if ($user->hasRole('contributor')) {
            $data = [];
            if (!is_null($nim = $validated['nim'])) {
                data_set($data, 'nim', $nim);
            }
            if (!is_null($study_program_id = $validated['study_program_id'])) {
                data_set($data, 'study_program_id', $study_program_id);
            }
            $user->author()->update($data);
        }

        if (!is_null($validated['avatar'])) {
            if (!is_null($user->avatar)) {
                if (Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                };
            }
            $validated['avatar'] = $validated['avatar']->store('avatars', 'public');
        } else {
            $validated['avatar'] = $user->avatar;
        }

        empty($validated['password']) ? $validated['password'] = $user->password : $validated['password'] = Hash::make($validated['password']);

        /** @var \App\Models\User $user*/
        $user->update([
            'name' => $validated['name'],
            'password' => $validated['password'],
            'avatar' => $validated['avatar']
        ]);

        return session()->flash('message', 'The profile was successfully updated.');
    }

    public function render()
    {
        $user = Auth::user();

        $study_programs = [];

        if ($user->hasRole('contributor')) {
            $study_programs = StudyProgramData::collect(StudyProgram::get());
        }

        return view('livewire.profile', compact('study_programs'));
    }
}
