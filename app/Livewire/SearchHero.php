<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\MetaData;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

class SearchHero extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $title = '';
    public string $category = 'skripsi';
    public string $year = '';
    public string $author = '';

    protected function queryString()
    {
        return [
            'category' => [
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
        $query = MetaData::query();

        $query->where('status', 'approve')->where('visibility', 'public');

        if ($title = $this->title) {
            $query->whereLike('title', "%$title%");
        }

        $query->whereHas(
            'categories',
            fn($query) => $query->where('slug', $this->category)
        );

        if ($year = $this->year) {
            $query->where('year', $year);
        }

        if ($author = $this->author) {
            $query->whereHas(
                'author.user',
                fn($query) => $query->whereLike('name', "%$author%")
            );
        }

        $repositories = $query->with([
            'author',
            'categories' => fn($query) => $query->where('slug', $this->category),
            'author.user',
            'author.studyProgram'
        ])
            ->orderByDesc('id')->paginate(10);

        $categories = Category::all();

        return view('livewire.search-hero', compact('repositories', 'categories'));
    }
}
