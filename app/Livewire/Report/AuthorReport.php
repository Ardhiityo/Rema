<?php

namespace App\Livewire\Report;

use Throwable;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contratcs\AuthorRepositoryInterface;
use App\Repositories\Contratcs\CategoryRepositoryInterface;
use App\Repositories\Contratcs\CoordinatorRepositoryInterface;

class AuthorReport extends Component
{
    public string|int $year = '';
    public array $includes = [];
    public string|int $nidn = '';

    public function mount()
    {
        $this->year = now()->year;
    }

    public function rules(): array
    {
        return [
            'year' => ['required', 'date_format:Y', 'exists:meta_data,year'],
            'includes.*' => ['nullable', 'exists:categories,slug'],
            'nidn' => ['required', 'exists:coordinators,nidn']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'includes.*' => 'includes',
            'nidn' => 'coordinator'
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
        $this->nidn = '';

        $this->resetErrorBag();
    }

    public function download()
    {
        $validated = $this->validate();

        try {
            $this->resetInput();
            return redirect()->route('reports.repositories.download', [
                'nidn' => $validated['nidn'],
                'year' => $validated['year'],
                'includes' => json_encode(isset($validated['includes']) ? $validated['includes'] : [])
            ]);
        } catch (Throwable $th) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'AuthorReport',
                        'method' => 'download',
                    ],
                    'data' => [
                        'year' => $this->year,
                        'includes' => $this->includes,
                        'nidn' => $this->nidn,
                    ]
                ],
                'message' => $th->getMessage()
            ], JSON_PRETTY_PRINT));

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
