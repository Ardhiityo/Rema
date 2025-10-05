<?php

namespace App\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Category extends Component
{
    use AuthorizesRequests;

    public function mount()
    {
        $this->authorize(
            'viewAny',
            \App\Models\Category::class
        );
    }

    public function render()
    {
        return view('livewire.category');
    }
}
