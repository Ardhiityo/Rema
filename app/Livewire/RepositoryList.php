<?php

namespace App\Livewire;

use App\Data\RepositoryData;
use App\Models\Repository;
use Livewire\Component;

class RepositoryList extends Component
{
    public function render()
    {
        $query = Repository::query();

        $repositories = RepositoryData::collect($query->paginate(10));

        return view('livewire.repository-list', compact('repositories'));
    }
}
