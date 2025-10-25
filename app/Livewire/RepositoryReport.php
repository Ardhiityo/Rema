<?php

namespace App\Livewire;

use Livewire\Component;
use App\Exports\RepositoryExport;
use Livewire\Attributes\Computed;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\Contratcs\AuthorRepositoryInterface;
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

        $year = $this->year;

        return Excel::download(
            new RepositoryExport($year, $this->includes),
            "Repositories $year" . '.pdf',
            \Maatwebsite\Excel\Excel::MPDF
        );
    }

    public function render()
    {
        $categories = $this->categoryRepository->all();

        return view('livewire.repository-report', compact('categories'));
    }
}
