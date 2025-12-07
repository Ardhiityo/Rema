<?php

namespace App\Livewire\Report;

use Throwable;
use Livewire\Component;
use App\Exports\RepositoryExport;
use Livewire\Attributes\Computed;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\Contratcs\AuthorRepositoryInterface;
use App\Repositories\Contratcs\CategoryRepositoryInterface;
use App\Repositories\Contratcs\CoordinatorRepositoryInterface;

class RepositoryReport extends Component
{
    public string|int $year = '';
    public array $includes = [];
    public string|int $coordinator_id = '';

    public function mount()
    {
        $this->year = now()->year;
    }

    public function rules(): array
    {
        return [
            'year' => ['required', 'date_format:Y', 'exists:meta_data,year'],
            'includes.*' => ['nullable', 'exists:categories,slug'],
            'coordinator_id' => ['required', 'exists:coordinators,id']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'includes.*' => 'includes',
            'coordinator_id' => 'coordinator'
        ];
    }

    #[Computed()]
    public function categoryRepository()
    {
        return app(CategoryRepositoryInterface::class);
    }

    #[Computed()]
    public function authorRepository(): AuthorRepositoryInterface
    {
        return app(AuthorRepositoryInterface::class);
    }

    #[Computed()]
    public function coordinatorRepository(): CoordinatorRepositoryInterface
    {
        return app(CoordinatorRepositoryInterface::class);
    }

    public function resetInput()
    {
        $this->year = '';
        $this->includes = [];
        $this->coordinator_id = '';

        $this->resetErrorBag();
    }

    public function download()
    {
        $validated = $this->validate();

        try {
            $year = $this->year;

            $coordinator_id = $validated['coordinator_id'];

            $coordinator_data = $this->coordinatorRepository->findById($coordinator_id);

            return Excel::download(
                export: new RepositoryExport(
                    year: $year,
                    includes: $this->includes,
                    coordinator_data: $coordinator_data
                ),
                fileName: "Repositories $year" . '.pdf',
                writerType: \Maatwebsite\Excel\Excel::MPDF
            );
        } catch (Throwable $th) {
            session()->flash('repository-failed', $th->getMessage());
        }
    }

    public function render()
    {
        $categories = $this->categoryRepository->all();
        $coordinators = $this->coordinatorRepository->all();

        return view(
            'livewire.report.repository',
            compact('categories', 'coordinators')
        );
    }
}
