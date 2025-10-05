<?php

namespace App\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Author extends Component
{
    use AuthorizesRequests;

    public function mount()
    {
        $this->authorize(
            'viewAny',
            \App\Models\Author::class
        );
    }

    public function render()
    {
        return view('livewire.author');
    }
}
