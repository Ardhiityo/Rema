<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Repository;
use App\Data\SearchHeroData;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

class SearchHero extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $title = '';
    public string $category = '';
    public string $year = '';
    public string $author = '';

    protected function queryString()
    {
        return [
            'type' => [
                'except' => ''
            ],
            'title' => [
                'except' => ''
            ],
            'year' => [
                'except' => ''
            ],
            'author' => [
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

        $query->where('status', 'approve')->where('visibility', 'public');

        if ($title = $this->title) {
            $query->whereLike('title', "%$title%");
        }

        if ($slug = $this->category) {
            $query->whereHas(
                'category',
                fn($query) => $query->where('slug', $slug)
            );
        }

        if ($year = $this->year) {
            $query->where('year', $year);
        }

        if ($author = $this->author) {
            $query->whereHas(
                'author',
                fn($query) => $query->whereLike('name', "%$author%")
            );
        }

        $repositories = SearchHeroData::collect(
            $query->with(['author', 'category', 'author.user', 'author.studyProgram'])
                ->orderByDesc('id')->paginate(12)
        );

        $categories = Category::all();

        return view('livewire.search-hero', compact('repositories', 'categories'));
    }
}
