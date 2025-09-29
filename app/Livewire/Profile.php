<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public $avatar = null;

    public int|null $user_id = null;

    public function mount()
    {
        $user = Auth::user();

        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:50'],
            'email' => ['required', 'email:dns', 'unique:users,email,' . $this->user_id],
            'password' => ['nullable', 'min:8', 'max:100'],
            'avatar' => ['nullable', 'file', 'mimes:jpg,png', 'max:1000']
        ];
    }

    public function resetInput()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    public function update()
    {
        $validated = $this->validate();

        $user = Auth::user();

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

        is_null($validated['password']) ? $validated['password'] = $user->password : $validated['password'] = Hash::make($validated['password']);

        $user->update($validated);

        $this->resetInput();
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
