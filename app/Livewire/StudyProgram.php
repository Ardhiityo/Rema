<?php

namespace App\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class StudyProgram extends Component
{
    use AuthorizesRequests;

    public function mount()
    {
        $this->authorize(
            'viewAny',
            \App\Models\StudyProgram::class
        );
    }

    public function render()
    {
        return view('livewire.study-program');
    }
}
