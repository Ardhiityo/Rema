<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Repository;
use App\Data\SearchHeroData;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

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

        $repositories = SearchHeroData::collect($query->with(['author', 'author.studyProgram'])
            ->paginate(1));

        return view('livewire.search-hero', compact('repositories'));
    }
}
