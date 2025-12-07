<?php

namespace App\Livewire\Coordinator;

use App\Repositories\Contratcs\CoordinatorRepositoryInterface;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class CoordinatorList extends Component
{
    use WithPagination;

    public string $keyword = '';

    #[On('refresh-coordinators')]
    public function getCoordinatorsProperty(CoordinatorRepositoryInterface $coordinatorRepository)
    {
        return $coordinatorRepository->findByFilters($this->keyword);
    }

    public function resetInput()
    {
        $this->keyword = '';
    }

    public function render()
    {
        return view('livewire.coordinator.list');
    }
}
