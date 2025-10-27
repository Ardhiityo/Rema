<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;

class CoordinatorForm extends Component
{
    public string $name = '';
    public string $position = '';
    public string|int $nidn = '';

    #[Computed()]
    public bool $is_update = false;

    #[Computed()]
    public function formTitle()
    {
        return $this->is_update ? 'Edit Coordinator' : 'Create New Coordinator';
    }

    public function resetInput()
    {
        $this->name = '';
        $this->position = '';
        $this->nidn = '';
    }

    public function render()
    {
        return view('livewire.coordinator-form');
    }
}
