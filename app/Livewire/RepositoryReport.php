<?php

namespace App\Livewire;

use App\Repositories\Contratcs\AuthorRepositoryInterface;
use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Repositories\Contratcs\CategoryRepositoryInterface;

class RepositoryReport extends Component
{
    public string|int $year = '';
    public array $includes = [];

    public function mount()
    {
        $this->year = now()->year;
    }

    public function rules(): array
    {
        return [
            'year' => ['required', 'date_format:Y', 'exists:meta_data,year'],
            'includes.*' => ['nullable', 'exists:categories,slug']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'includes.*' => 'includes'
        ];
    }

    #[Computed()]
    public function categoryRepository()
    {
        return app(CategoryRepositoryInterface::class);
    }

    #[Computed()]
    public function authorRepository()
    {
        return app(AuthorRepositoryInterface::class);
    }

    public function resetInput()
    {
        $this->year = '';
        $this->includes = [];
    }

    public function download()
    {
        $this->validate();

        $res = $this->authorRepository->reports($this->year, $this->includes);

        logger(json_encode($res, JSON_PRETTY_PRINT));

        dd($res);
    }

    public function render()
    {
        $categories = $this->categoryRepository->all();

        return view('livewire.repository-report', compact('categories'));
    }
}
