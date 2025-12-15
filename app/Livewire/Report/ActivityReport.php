<?php

namespace App\Livewire\Report;

use Throwable;
use Livewire\Component;
use App\Exports\ActivityExport;
use Maatwebsite\Excel\Facades\Excel;

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

    public function download()
    {
        $this->validate();

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
            logger(json_encode($th->getMessage(), JSON_PRETTY_PRINT));
            session()->flash('activity-failed', $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.report.activity');
    }
}
