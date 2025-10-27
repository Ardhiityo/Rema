<?php

namespace App\Livewire;

use Throwable;
use Livewire\Component;
use App\Exports\ActivityExport;
use Livewire\Attributes\Computed;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\Contratcs\CoordinatorRepositoryInterface;

class ActivityReport extends Component
{
    public string $year = '';
    public string|int $coordinator_id = '';

    public function mount()
    {
        $this->year = now()->year;
    }

    public function rules()
    {
        return [
            'year' => ['required', 'date_format:Y', 'exists:meta_data,year'],
            'coordinator_id' => ['required', 'exists:coordinators,id']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'coordinator_id' => 'coordinator'
        ];
    }

    public function resetInput()
    {
        $this->year = '';

        $this->resetErrorBag();
    }

    #[Computed()]
    public function coordinatorRepository()
    {
        return app(CoordinatorRepositoryInterface::class);
    }

    public function download()
    {
        $validated = $this->validate();

        try {
            $year = $this->year;

            $coordinator_id = $validated['coordinator_id'];

            $coordinator_data = $this->coordinatorRepository->findById($coordinator_id);

            return Excel::download(
                export: new ActivityExport(
                    year: $year,
                    coordinator_data: $coordinator_data
                ),
                fileName: "Activities $year" . '.pdf',
                writerType: \Maatwebsite\Excel\Excel::MPDF
            );
        } catch (Throwable $th) {
            session()->flash('activity-failed', $th->getMessage());
        }
    }

    public function render()
    {
        $coordinators = $this->coordinatorRepository->all();

        return view('livewire.activity-report', compact('coordinators'));
    }
}
