<?php

namespace App\Livewire;

use App\Repositories\Contratcs\ActivityRepositoryInterface;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Activity extends Component
{
    #[Computed()]
    public function activityRepository()
    {
        return app(ActivityRepositoryInterface::class);
    }

    public function render()
    {
        $activities = $this->activityRepository->all();

        return view('livewire.activity', compact('activities'));
    }
}
