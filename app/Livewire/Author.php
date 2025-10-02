<?php

namespace App\Livewire;

use Livewire\Component;
use App\Data\AuthorData;
use App\Models\StudyProgram;
use Livewire\WithPagination;
use App\Data\StudyProgramData;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class Author extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public string $keyword = '';
    public string $name = '';
    public string $nim = '';
    public string $email = '';
    public string $password = '';
    public $avatar;
    public int|null $study_program_id = null;
    public int|null $author_id = null;
    public int|null $user_id = null;
    public bool $is_update = false;

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
            ]
        ];
    }

    #[Computed()]
    public function formTitle()
    {
        return $this->is_update ? 'Edit Author' : 'Create New Author';
    }

    public function create()
    {
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
            'status' => 'approve'
        ]);

        $this->resetInput();

        return session()->flash('message', 'The author was successfully created.');
    }

    public function edit($user_id)
    {
        $user = \App\Models\User::find($user_id);

        $this->author_id = $user->author->id;
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->nim = $user->author->nim;
        $this->email = $user->email;
        $this->study_program_id = $user->author->study_program_id;

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
            'study_program_id' => $validated['study_program_id']
        ]);

        $this->is_update = false;

        $this->resetInput();
    }

    public function deleteConfirm($user_id)
    {
        $user = \App\Models\User::find($user_id);

        $this->user_id = $user->id;
    }

    public function delete()
    {
        $user = \App\Models\User::find($this->user_id);

        if (!is_null($user->avatar)) {
            if (Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
        }

        $user->delete();

        return session()->flash('message', 'The author was successfully deleted.');
    }

    public function resetInput()
    {
        $this->reset();
        $this->resetPage();
        $this->resetErrorBag();
    }

    public function render()
    {
        $query = \App\Models\Author::query();

        $query->where('status', 'approve');

        if ($keyword = $this->keyword) {
            $query->whereLike('name', "%$keyword%")->orWhere('nim', $keyword);
        }

        $query->with(['studyProgram', 'user']);

        $authors = AuthorData::collect(
            $query->orderByDesc('id')->paginate(10)
        );
        $study_programs = StudyProgramData::collect(StudyProgram::get());

        return view('livewire.author', compact('authors', 'study_programs'));
    }
}
