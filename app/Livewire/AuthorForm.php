<?php

namespace App\Livewire;

use Livewire\Component;
use App\Data\AuthorData;
use Livewire\Attributes\On;
use App\Models\StudyProgram;
use Livewire\WithFileUploads;
use App\Data\StudyProgramData;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthorForm extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string|null $nim = '';
    public string $email = '';
    public string $password = '';
    public $avatar;
    public string $status = '';
    public int|null $study_program_id = null;
    public int|null $author_id = null;
    public int|null $user_id = null;
    public bool $is_update = false;

    #[Computed()]
    public function formTitle()
    {
        return $this->is_update ? 'Edit Author' : 'Create New Author';
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:50'],
            'nim' => $this->is_update ? [
                'required',
                'min:8',
                'max:50',
                'unique:authors,nim,' . $this->author_id
            ] : [
                'required',
                'min:8',
                'max:50',
                'unique:authors,nim'
            ],
            'study_program_id' => ['required', 'exists:study_programs,id'],
            'avatar' => ['nullable', 'file', 'mimes:jpg,png', 'max:1000'],
            'email' => $this->is_update ? [
                'required',
                'email:dns',
                'unique:users,email,' . $this->user_id
            ] : [
                'required',
                'email:dns',
                'unique:users,email'
            ],
            'password' => $this->is_update ? [
                'nullable',
                'min:8',
                'max:50'
            ] : [
                'required',
                'min:8',
                'max:50'
            ],
            'status' => ['required', 'in:approve,reject,pending']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'study_program_id' => 'study program'
        ];
    }

    public function create()
    {
        $this->status = 'approve';

        $validated = $this->validate();

        if (!is_null($validated['avatar'])) {
            $validated['avatar'] = $validated['avatar']->store('avatars', 'public');
        }

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'avatar' => $validated['avatar']
        ]);

        $user->author()->create([
            'nim' => $validated['nim'],
            'study_program_id' => $validated['study_program_id'],
            'status' => $validated['status']
        ]);

        $this->resetInput();

        $this->dispatch('refresh-authors');

        return session()->flash('message', 'The author was successfully created.');
    }

    #[On('author-edit')]
    public function edit($author_id)
    {
        $author = AuthorData::fromModel(
            \App\Models\Author::find($author_id)
                ->load(['user', 'studyProgram'])
        );
        $this->user_id = $author->user_id;
        $this->author_id = $author->author_id;
        $this->name = $author->name;
        $this->nim = $author->nim;
        $this->email = $author->email;
        $this->study_program_id = $author->study_program_id;
        $this->status = $author->status;

        $this->is_update = true;
    }

    public function update()
    {
        $validated = $this->validate();

        $user = \App\Models\User::find($this->user_id);

        if (!is_null($validated['avatar'])) {
            $validated['avatar'] = $validated['avatar']->store('avatars', 'public');
        } else {
            $validated['avatar'] = $user->avatar;
        }

        if (!is_null($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            $validated['password'] = $user->password;
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);

        $user->author()->update([
            'nim' => $validated['nim'],
            'study_program_id' => $validated['study_program_id'],
            'status' => $validated['status']
        ]);

        $this->is_update = false;

        $this->resetInput();

        $this->dispatch('refresh-authors');

        return session()->flash('message', 'The author was successfully updated.');
    }

    #[On('author-delete-confirm')]
    public function deleteConfirm($author_id)
    {
        $author = \App\Models\Author::find($author_id);

        $this->user_id = $author->user->id;
    }

    #[On('author-delete')]
    public function delete()
    {
        $user = \App\Models\User::find($this->user_id);

        if (!is_null($user->avatar)) {
            if (Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
        }

        $user->delete();

        $this->dispatch('refresh-authors');

        return session()->flash('message', 'The author was successfully deleted.');
    }

    public function resetInput()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    public function render()
    {
        $study_programs = StudyProgramData::collect(StudyProgram::get());

        return view('livewire.author-form', compact('study_programs'));
    }
}
