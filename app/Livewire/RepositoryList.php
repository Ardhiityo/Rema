<?php

namespace App\Livewire;

use App\Data\RepositoryData;
use App\Models\Repository;
use Livewire\Component;

class RepositoryList extends Component
{
    public string $keyword = '';
    public string $title = '';

    public int|null $repository_id = null;

    public function deleteConfirm($repository_slug)
    {
        $repository = Repository::where('slug', $repository_slug)->first();
        $this->repository_id = $repository->id;
        $this->title = $repository->title;
    }

    public function delete()
    {
        Repository::find($this->repository_id)->delete();
    }

    public function render()
    {
        $query = Repository::query();

        if ($keyword = $this->keyword) {
            $query->whereLike('title', "%$keyword%");
        }

        $repositories = RepositoryData::collect($query->paginate(10));

        return view('livewire.repository-list', compact('repositories'));
    }
}
