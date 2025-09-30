<?php

namespace App\Livewire;

use App\Data\SearchHeroData;
use App\Models\Repository;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class SearchHero extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $title = '';
    public string $type = '';

    protected function queryString()
    {
        return [
            'type' => [
                'except' => ''
            ],
            'title' => [
                'except' => ''
            ]
        ];
    }

    public function resetInput()
    {
        $this->reset();
        $this->resetPage();
    }

    public function updatedPage()
    {
        $this->dispatch('scroll-to-search');
    }

    #[Layout('layouts.welcome')]
    public function render()
    {
        $query = Repository::query();

        if ($title = $this->title) {
            $query->whereLike('title', "%$title%");
        }

        if ($type = $this->type) {
            $query->where('type', $type);
        }

        $repositories = SearchHeroData::collect($query->with('author')
            ->paginate(1));

        return view('livewire.search-hero', compact('repositories'));
    }
}
