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

    public function mount()
    {
        $this->year = now()->year;
    }

    public function rules()
    {
        return [
            'year' => ['required', 'date_format:Y', 'exists:meta_data,year']
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

            return Excel::download(
                export: new ActivityExport(
                    year: $year
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
