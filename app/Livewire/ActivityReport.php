<?php

namespace App\Livewire;

use Livewire\Component;

class ActivityReport extends Component
{
    public string $year = '';

    public function rules()
    {
        return [
            'year' => ['required', 'date_format:Y']
        ];
    }

    public function resetInput()
    {
        $this->year = '';
        $this->resetErrorBag();
    }

    public function download()
    {
        $this->validate();
    }

    public function render()
    {
        return view('livewire.activity-report');
    }
}
