<?php

namespace App\Livewire;

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

        return Excel::download(
            new ActivityExport($this->year),
            'invoices.pdf',
            \Maatwebsite\Excel\Excel::MPDF
        );
    }

    public function render()
    {
        return view('livewire.activity-report');
    }
}
